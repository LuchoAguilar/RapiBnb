<?php
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/ofertaAlquiler.php');

    class OfertaAlquilerController extends Controller{

        private $ofertaModel;
        private $userSession;

        public function __construct($connect,$session){
            $this->ofertaModel = new OfertaAlquiler($connect);
            $this->userSession = new ControladorDeSessiones($session,$connect);
        }

        public function home(){
            if($this->userSession->Roll() === 'usuarioLog'){
                $this->render('publicarOferta', [] ,'site');
            }else{
                header("Location: http://localhost/PM/Public/");
            }
        }

        public function create(){

        }

        public function edit(){

        }

        public function delete(){

        }

        public function table(){
            $result = Result();
            $userLogin = $this->userSession->ID();
            $userVerf = $this->userSession->esVerificado();
            $ofertasUsuario = $this->ofertaModel->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'creadorID' , $userLogin);
            $result->success = true;
            $result->result = [
                'ofertas' => $ofertasUsuario,
                'userVerificado' => $userVerf
            ];            
            echo json_encode($result);
        }

        public function update(){

        }
        
    }


?>