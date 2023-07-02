import sys

#Graficos
import matplotlib.pyplot as plt

#Manejo de datos
import pandas as pd
import numpy as np
import cv2
from skimage import io

#Modelo IA
import tensorflow as tf
from tensorflow.keras import backend as K


def prediction(test, model, model_seg):
  '''
  Función de predicción que toma como entrada un marco de datos que contiene ImageID y realiza dos tipos de predicción sobre la imagen
  Inicialmente, la imagen pasa por la red de clasificación que predice si la imagen tiene defectos o no.
  Si el modelo está 99% seguro de que la imagen no tiene defectos, entonces la imagen se etiqueta como sin defectos.
  Si el modelo no está seguro, pasa la imagen a la red de segmentación, que comprueba de nuevo si la imagen tiene defectos o no.
  '''

  #Directorio
  directory = "./"

  #Creando una lista vacía para guardar los resultados
  mask = []
  image_id = []
  has_mask = []

  #Iterando para cada imagen en la data del test
  for i in test.image_path:

    path = directory + str(i)

    #Obteniendo imagen
    img = io.imread(path)

    #Normalizando imagen
    img = img * 1./255.

    #Redimensionando imagen
    img = cv2.resize(img,(256,256))

    #Convirtiendo imagen a un arreglo de tipo float64
    img = np.array(img, dtype = np.float64)
    
    #Redimensionando imagen de 256,256,3 a 1,256,256,3
    img = np.reshape(img, (1,256,256,3))

    #Realizando clasificación de tumor o no tumor
    is_defect = model.predict(img)

    #Si no hay tumor, añadimos los resultados a la lista y se salta a la siguiente imágen
    if np.argmax(is_defect) == 0:
      image_id.append(i)
      has_mask.append(0)
      mask.append('No mask')
      continue

    #Obteniendo imagen
    img = io.imread(path)

    #Creando un arreglo vacío de tamaño 1,256,256,3
    X = np.empty((1, 256, 256, 3))

    #Redimensionando imagen un convirtiendo a un arreglo de tipo float64
    img = cv2.resize(img,(256,256))
    img = np.array(img, dtype = np.float64)

    #Standarizando la imágen
    img -= img.mean()
    img /= img.std()

    #Redimensionando tamaño de imagen de 256,256,3 a 1,256,256,3
    X[0,] = img

    #Realizando segmentación de tumor
    predict = model_seg.predict(X)

    #si la suma de los valores previstos es igual a 0, no hay tumor
    if predict.round().astype(int).sum() == 0:
        image_id.append(i)
        has_mask.append(0)
        mask.append('No mask')
    else:
    #si la suma de los valores de los píxeles es superior a 0, entonces hay tumor
        image_id.append(i)
        has_mask.append(1)
        mask.append(predict)


  return image_id, mask, has_mask
        

'''
We need a custom loss function to train this ResUNet.So,  we have used the loss function as it is from https://github.com/nabsabraham/focal-tversky-unet/blob/master/losses.py


@article{focal-unet,
  title={A novel Focal Tversky loss function with improved Attention U-Net for lesion segmentation},
  author={Abraham, Nabila and Khan, Naimul Mefraz},
  journal={arXiv preprint arXiv:1810.07842},
  year={2018}
}
'''
def tversky(y_true, y_pred, smooth = 1e-6):
    y_true_pos = K.flatten(y_true)
    y_pred_pos = K.flatten(y_pred)
    true_pos = K.sum(y_true_pos * y_pred_pos)
    false_neg = K.sum(y_true_pos * (1-y_pred_pos))
    false_pos = K.sum((1-y_true_pos)*y_pred_pos)
    alpha = 0.7
    return (true_pos + smooth)/(true_pos + alpha*false_neg + (1-alpha)*false_pos + smooth)

def focal_tversky(y_true,y_pred):
    y_true = tf.cast(y_true, tf.float32)
    y_pred = tf.cast(y_pred, tf.float32)
    pt_1 = tversky(y_true, y_pred)
    gamma = 0.75
    return K.pow((1-pt_1), gamma)

################################################################
nombreArchivo = sys.argv[1]
nombreOuput = nombreArchivo[:-4]+'.jpg'

#CLASIFICACION
#Cargamos imagen a evaluar
test = pd.DataFrame({
    'image_path': ['uploads/'+nombreArchivo],
})

#Cargamos modelo clasificador preentrenado
with open('util/classificador-resnet-modelo1.json', 'r') as json_file:
    json_savedModel= json_file.read()
modelo_clasificador = tf.keras.models.model_from_json(json_savedModel)
modelo_clasificador.load_weights('util/clasificador-resnet-pesos.hdf5')
modelo_clasificador.compile(loss = 'categorical_crossentropy', optimizer='adam', metrics= ["accuracy"])

#SEGMENTACION
#Cargamos modelo segmentador preentrenado
with open('util/ResUNet-MRI.json', 'r') as json_file:
    json_savedModel= json_file.read()
#cargar la arquitectura del modelo
model_seg = tf.keras.models.model_from_json(json_savedModel)
model_seg.load_weights('util/weights_seg.hdf5')
adam = tf.keras.optimizers.Adam(lr = 0.05, epsilon = 0.1)
model_seg.compile(optimizer = adam, loss = focal_tversky, metrics = [tversky])

#Realizamos la predicción
image_id, mask, has_mask = prediction(test, modelo_clasificador, model_seg)

#Guardamos los resultados de la prediccion en un dataframe
df_pred = pd.DataFrame({'image_path': image_id,'predicted_mask': mask,'has_mask': has_mask})
      
#Representación gráfica de los resultados del modelo
fig, axs = plt.subplots(1, 3, figsize=(15, 5))
for i in range(len(df_pred)):
  if df_pred['has_mask'][i] == 1:
    print('TUMOR')
    img = io.imread(df_pred.image_path[i])
    img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    axs[0].title.set_text("MRI del Cerebro")
    axs[0].imshow(img)

    predicted_mask = np.asarray(df_pred.predicted_mask[i])[0].squeeze().round()
    axs[1].title.set_text("Máscara predicha por la IA")
    axs[1].imshow(predicted_mask)

    img_ = io.imread(df_pred.image_path[i])
    img_ = cv2.cvtColor(img_, cv2.COLOR_BGR2RGB)
    img_[predicted_mask == 1] = (0, 255, 0)
    axs[2].title.set_text("MRI con la máscara predicha por la IA")
    axs[2].imshow(img_)
    break
  else:
     print('LIMPIO')
     break

fig.savefig("results/"+nombreOuput)