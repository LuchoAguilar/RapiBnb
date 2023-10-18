<?php
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/administrador.php');

    class AdministradorController extends Controller{
        
        private $administradorModel;
        private $userSession;

        public function __construct($connect,$session){
            $this->administradorModel = new Administrador($connect);
            $this->userSession = new ControladorDeSessiones($session,$connect);
        }

        public function home(){

        }

        public function verificar(){

        }
    }
?>