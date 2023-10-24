<?php
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');

    class PageController extends Controller{

        private $userSessionControl;

        public function __construct($connect,$session){
            $this->userSessionControl = new ControladorDeSessiones($session,$connect);
        }

        public function home(){
            if($this->userSessionControl->Roll() === NO_LOG){
                $this->render('home',[],'noLog');
            }elseif($this->userSessionControl->Roll() === LOG){
                $this->render('home',[],'site');
            }elseif($this->userSessionControl->Roll() === ADMIN){
                $this->render('administrador',[],'admin');
            }
        }
        public function listar(){
            echo "listar";
        }
        public function logOut(){
            echo "cerrar session";
        }
        public function eliminar(){
            echo "eliminar";
        }
    }
?>