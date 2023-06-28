    <!--FORMULARIO-->
    <div class="p-y-lg light row-col">
      <div class="row m-t-lg m-b-md">
        <div class="box col-md-6 offset-md-3 lt m-b">

          <div class="box-header purple">
            <h3>Input</h3>
            <small>Por favor ingrese sus datos y la imágen MRI</small>
          </div>

          <div class="box-body purple-100">
            <form role="form" method="post" target="">
                <div class="padding ng-scope">

                    <div class="form-group">
                      <label for="dni">DNI</label>
                      <input name="dni" type="text" class="form-control" placeholder="Ingrese DNI">
                    </div>

                    <div class="form-group">
                      <label for="nombres">Nombres</label>
                      <input name="nombres" type="text" class="form-control" placeholder="Ingrese nombres">
                    </div>

                    <div class="form-group">
                      <label for="apellidos">Apellidos</label>
                      <input name="apellidos" type="text" class="form-control" placeholder="Ingrese apellidos">
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="fileInput">Seleccione una imagen:</label>
                            <input type="file" name="file" id="fileInput" onchange="handleFileSelect(event)">

                            <div class="col-sm-8">
                                <div ondragover="handleDragOver(event)" ondrop="handleDrop(event)">
                                  <div over-class="b-danger" id="dropzone" class="b-info b-2x b-dashed p-a-md text-center">
                                      </br></br>O arrastre el archivos a esta zona</br></br></br>
                                  </div>
                                </div>
                            </div>

                            <input type="hidden" id="imagen" name="imagen"/>

                            <div class="col-sm-4">  
                                <div class="inline">
                                <div class="box img-square" id="image-preview"></div>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </div>

                <footer class="p-a text-center">
                    <button type="submit" id="enviado" class="btn m-b-sm purple">Realizar Consulta</button>
                </footer>

            </form>

            <!--Mensaje de error-->
            <br><br>
            <div style="color:red" class="">
              <?php echo $msg ?>
            </div>
            <br>
          </div>

        </div>
      </div>
    </div>

  </div>