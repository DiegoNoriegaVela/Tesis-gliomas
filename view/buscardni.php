    <!--FORMULARIO-->
    <div class="p-y-lg light row-col">
      <div class="row m-t-lg m-b-md">
        <div class="box col-md-6 offset-md-3 lt m-b">

          <div class="box-header purple">
            <h3>Input</h3>
            <small>Por favor ingrese su DNI</small>
          </div>

          <div class="box-body purple-100">
            <form role="form" method="post" target="">
                <div class="padding ng-scope">

                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input name="dni" type="text" class="form-control" placeholder="Ingrese DNI">
                    </div> 

                </div>

                <footer class="p-a text-center">
                    <button type="submit" class="btn m-b-sm purple">Buscar</button>
                </footer>

                <!--Mensaje de error-->
                <br><br>
                <div style="color:red" class="">
                <?php echo $msg ?>
                </div>
                <br>

            </form>
          </div>

        </div>
      </div>
    </div>

</div>