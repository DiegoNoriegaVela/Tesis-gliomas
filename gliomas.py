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

#personalizado
from utilities import prediction, focal_tversky, tversky

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