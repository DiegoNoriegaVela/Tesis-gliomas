<?php
require_once "model/database.php";
if(!isset($_REQUEST['c'])){
    require_once "controller/users.controller.php";
    $controlador=new UsersControlador();
    call_user_func(array($controlador,"Inicio"));

}else{
    $controlador = $_REQUEST['c'];
    require_once "controller/$controlador.controller.php";
    $controlador=ucwords($controlador)."Controlador";
    $controlador= new $controlador;
    $accion=isset($_REQUEST['a'])?$_REQUEST['a']:"Inicio";
    call_user_func(array($controlador,$accion));
}