    <!--PIE DE PÁGINA-->
    <footer class="purple-100 pos-rlt">
        <div class="footer dk">
            <div class="p-a-md">
                <div class="row footer-bottom">
                    <div class="col-sm-8">
                        <small class="text-muted"><strong>Detector de gliomas</strong> por Diego Noriega y Ariana Sandoval</small>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-sm-right text-xs-left">
                            <svg width="32" height="32">
                            <image xlink:href="assets/images/sanmarcos.svg" width="32" height="32" />
                            </svg>
                            <span class="hidden-folded inline"><strong>Tesis UNMSM</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
    function handleDragOver(event) {
      event.preventDefault();
      event.dataTransfer.dropEffect = "copy";
      document.getElementById("dropzone").classList.add("dragover");
    }

    function handleFileSelect(event) {
      var files = event.target.files;
      if (files.length > 0) {
        handleFile(files[0]);
      }
    }

    function handleFile(file) {
      if (file.type.match("image.*")) {
          var formData = new FormData();
          formData.append("file", file);

          var xhr = new XMLHttpRequest();
          xhr.open("POST", "?c=users&a=SubirImagen", true);
          xhr.onload = function() {
            if (xhr.status === 200) {
              var imageURL = "uploads/" + file.name; // Ruta donde se guardó la imagen
              var img = document.createElement("img");
              var nombre_imagen = document.getElementById("imagen");
              nombre_imagen.value = file.name;
              img.src = imageURL;
              img.alt = file.name;
              document.getElementById("image-preview").appendChild(img);
              alert("La imagen se ha subido exitosamente.");
            } else if(xhr.status === 500){
              alert("Error al mover la imagen a la ubicación deseada.");
            } else {
              alert("Hubo un error al subir la imagen.");
            }
          };
          xhr.send(formData);
      }
    }

    function handleDrop(event) {
      event.preventDefault();
      document.getElementById("dropzone").classList.remove("dragover");

      var files = event.dataTransfer.files;
      if (files.length > 0) {
        var file = files[0];
        if (file.type.match("image.*")) {
          var formData = new FormData();
          formData.append("file", file);

          var xhr = new XMLHttpRequest();
          xhr.open("POST", "?c=users&a=SubirImagen", true);
          xhr.onload = function() {
            if (xhr.status === 200) {
              var imageURL = "uploads/" + file.name; // Ruta donde se guardó la imagen
              var img = document.createElement("img");
              var nombre_imagen = document.getElementById("imagen");
              nombre_imagen.value = file.name;
              img.src = imageURL;
              img.alt = file.name;
              document.getElementById("image-preview").appendChild(img);
              alert("La imagen se ha subido exitosamente.");
            } else if(xhr.status === 500){
              alert("Error al mover la imagen a la ubicación deseada.");
            } else {
              alert("Hubo un error al subir la imagen.");
            }
          };
          xhr.send(formData);
        } else {
          alert("Por favor, selecciona una imagen.");
        }
      }
    }
  </script>

</body>
</html>