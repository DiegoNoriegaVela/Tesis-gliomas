<?php
require_once "model/users.php";



class UsersControlador{
    private $user;

    public function __construct(){
        $this->user=new User();
    }

    /**********INTEFACES***************/

    public function Inicio(){
        require_once "view/encabezado.php";
        require_once "view/inicio.php";
        require_once "view/piePag.php";
    }

    public function SubirImagen(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
              
              $nombreArchivo = $_FILES['file']['name'];
              $rutaTemporal = $_FILES['file']['tmp_name'];
              $rutaArchivo = 'uploads/' .$nombreArchivo;
              
              if (move_uploaded_file($rutaTemporal, $rutaArchivo)) {
                // La imagen se ha subido correctamente
                http_response_code(200);
              } else {
                // Error al mover la imagen a la ubicación deseada
                http_response_code(500);
              }
            } else {
              // No se envió ninguna imagen o ocurrió un error en la subida
              http_response_code(600);
            }
          }
    }

    public function BuscarDNI(){
        $msg = "";

        if(isset($_POST['dni'])){
            $userExist=$this->user->ObtenerUsuario($_POST['dni']);

            if($userExist!=null){
                $msg.="DNI válido <br>";
                echo '<meta http-equiv="refresh" content="2;url=?c=users&a=MostrarResultados&dni=' . $_POST['dni']. '">';
            }else{
                $msg.="El DNI ingresado no está registrado<br>";
            }
        }

        require_once "view/encabezado.php";
        require_once "view/buscardni.php";
        require_once "view/piePag.php";
    }

    public function MostrarResultados(){
        if(isset($_GET['dni'])){
            $usuario=$this->user->ObtenerUsuario($_GET['dni']);
        }


        if(isset($_POST['nombreArchivo'])){
            $rutaArchivo = 'results/'.$_POST['nombreArchivo'];

            if (file_exists($rutaArchivo)) {
                // Configurar las cabeceras de la respuesta HTTP
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . 'resultado_'.$usuario->getUsers_dni().'.jpg');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($rutaArchivo));

                // Leer y enviar el contenido del archivo
                readfile($rutaArchivo);
                exit;
            }
        }

        require_once "view/encabezado.php";
        require_once "view/resultado.php";
        require_once "view/piePag.php";
    }

    public function NuevaConsulta(){
        $dni = "";
        $nombres = "";
        $apellidos = "";
        $img = "";
        $tumor = "";
        $msg = "";

        if( isset($_POST['dni']) && isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['imagen'])) {
            $dni = strip_tags($_POST['dni']);
            $nombres = strip_tags($_POST['nombres']);
            $apellidos = strip_tags($_POST['apellidos']);
            $img = strip_tags($_POST['imagen']);

            #$imgOriginal = 'uploads/TCGA_HT_7680_19970202_5.tif';
            #$img = 'results/ojalata.jpg';
            $nombreSinExtension = pathinfo($img, PATHINFO_FILENAME);
            $nombreArchivoOutput = $nombreSinExtension.'.'.'jpg';

            $userExist = $this->user->ObtenerUsuario($dni);

            if($userExist==null){
                $output = shell_exec('python gliomas.py 2>&1'.$img); 
                $palabraBuscada = 'TUMOR';
                // Buscar la palabra en la salida
                if (stripos($output, $palabraBuscada) !== false) {
                    $tumor = 1;
                } else {
                    $tumor = 0;
                }

                $u=new User();
                $u->setUsers_dni($dni);
                $u->setUsers_nombres($nombres);
                $u->setUsers_apellidos($apellidos);    
                $u->setUsers_img($nombreArchivoOutput);
                $u->setUsers_tumor($tumor);
                $this->user->InsertarUsuario($u);

                echo '<meta http-equiv="refresh" content="4;url=?c=users&a=MostrarResultados&dni=' . $dni . '">';
            }else{
                $msg.="El DNI ingresado ya existe<br>";
            }

        }else{
            $msg = "Complete el formulario";
        }


        require_once "view/encabezado.php";
        require_once "view/consulta.php";
        require_once "view/piePagConsulta.php";
    }

}

