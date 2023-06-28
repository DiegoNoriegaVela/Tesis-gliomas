<?php
class database{
    const servidor="localhost";
    const usuariobd="root";
    const clave = "";
    const nombrebd="tesis_gliomas";

    public static function Conectar(){
        try{
            $conexion=new PDO("mysql:host=".self::servidor.";dbname=".self::nombrebd.
            ";charser=utf8",self::usuariobd,self::clave);
            $conexion->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
            return $conexion;
        }catch(PDOException $e){
            die("Fallo ".$e->getMessage());

        }
    }
}