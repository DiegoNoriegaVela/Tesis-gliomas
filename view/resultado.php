    <!--EJECUTAR PYTHON-->

    <!--MOSTRAR RESULTADOS-->
    <div class="p-y-lg deep-purple-50 row-col">
        <div class="offset-md-2">
            <h4><b>Resultado: </b></h4>
        </div>
        <div class="container p-y-lg text-primary-hover">
            <div class="row">
                <div class="container p-y-lg pos-rlt">
                    <div class="modal-content box-shadow-md purple-A100 m-b">
                        <div class="modal-header">
                            <h5 class="modal-title">Paciente: <b><?=$usuario->getUsers_nombres()?> <?=$usuario->getUsers_apellidos()?></b></h5>
                        </div>
                        <div class="modal-body">
                            <?php
                            if($usuario->getUsers_tumor()==0){
                            ?>
                            <div class="offset-md-1">
                                <h5>El paciente <span class="label label-lg green">no tiene glioma cerebral</span>, análisis realizado con un <b>98%</b> de presición, se recomienda verificarlo con un médico.</h5>
                            </div>
                            </br></br>
                            <?php
                            }else if($usuario->getUsers_tumor()==1){
                            ?>
                            <div class="offset-md-1">
                                <h5>El paciente <span class="label label-lg red">tiene un glioma cerebral</span>, detectado con un <b>98%</b> de precisión, se recomienda derivar a un médico.</h5>
                            </div>
                            </br></br>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if($usuario->getUsers_tumor()==0){
        ?>
        <div class="offset-md-2">
            <h4><b>MRI original analizada: </b></h4>
        </div>
        <div class="imagen-centrada">
            <img src="uploads/<?=$usuario->getUsers_img()?>">
        </div>
        <form role="form" method="post" target="">
            <input type="hidden" name="nombreArchivoInput" value="<?=$usuario->getUsers_img()?>">
            <footer class="p-a text-center">
                    <button type="submit" class="btn m-b-sm purple">Descargar imagen</button>
            </footer>
        </form>
        <?php
        }else if($usuario->getUsers_tumor()==1){
        ?>
        <div class="offset-md-2">
            <h4><b>Imágen obtenida del análsis: </b></h4>
        </div>
        <div class="imagen-centrada">
            <img src="results/<?=$usuario->getUsers_img()?>">
        </div>
        <form role="form" method="post" target="">
            <input type="hidden" name="nombreArchivoOutput" value="<?=$usuario->getUsers_img()?>">
            <footer class="p-a text-center">
                    <button type="submit" class="btn m-b-sm purple">Descargar resultado</button>
            </footer>
        </form>
        <div class="offset-md-2">
            <h4><b>MRI original analizada: </b></h4>
        </div>
        <div class="imagen-centrada">
            <img src="uploads/<?=$usuario->getUsers_img()?>">
        </div>
        <form role="form" method="post" target="">
            <input type="hidden" name="nombreArchivoInput" value="<?=$usuario->getUsers_img()?>">
            <footer class="p-a text-center">
                    <button type="submit" class="btn m-b-sm purple-200">Descargar MRI original</button>
            </footer>
        </form>
        <?php
        }
        ?>

    </div>

</div>