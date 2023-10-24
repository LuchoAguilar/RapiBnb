<?php 
    require_once(__DIR__.'/../Services/conexion.php');
    require_once(__DIR__.'/../Services/session.php');
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/documentacion.php');

    $conexion = new Conexion();
    $connect = $conexion->getConexion();
    $session = new Session();

    $userSession = new ControladorDeSessiones($session,$connect);
    $usuario = new Usuario($connect);
    $documentacion = new Documentacion($connect);
?>