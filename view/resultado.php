    <!--EJECUTAR PYTHON-->

    <!--MOSTRAR RESULTADOS-->
    <div class="p-y-lg deep-purple-50 row-col">
      <div class="container p-y-lg text-primary-hover">
        
        <div class="row">
            <div class="container p-y-lg pos-rlt">
                <?php
                if($usuario->getUsers_tumor()==0){
                ?>
                <div class="offset-md-1">
                    <h5>El paciente <?=$usuario->getUsers_nombres()?> <?=$usuario->getUsers_apellidos()?> <span class="label label-lg green">no tiene glioma cerebral</span></h5>
                </div>
                </br></br></br></br></br></br></br></br></br></br></br>
                <?php
                }else if($usuario->getUsers_tumor()==1){
                ?>
                <div class="offset-md-1">
                    <h5>El paciente <?=$usuario->getUsers_nombres()?> <?=$usuario->getUsers_apellidos()?> <span class="label label-lg red">tiene un glioma cerebral</span></h5>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
      </div>

        <?php
        if($usuario->getUsers_tumor()==1){
        ?>
        <div class="imagen-centrada">
            <img src="results/<?=$usuario->getUsers_img()?>">
        </div>

        <form role="form" method="post" target="">
            <input type="hidden" name="nombreArchivo" value="<?=$usuario->getUsers_img()?>">
            <footer class="p-a text-center">
                    <button type="submit" class="btn m-b-sm purple">Descargar imagen</button>
            </footer>
        </form>
        <?php
        }
        ?>

    </div>

</div>