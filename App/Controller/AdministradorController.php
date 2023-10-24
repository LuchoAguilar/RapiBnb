<?php
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/administrador.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/documentacion.php');

    class AdministradorController extends Controller{
        
        private $administradorModel;
        private $userSession;
        private $usuarios;
        private $documentacion;

        public function __construct($connect,$session){
            $this->administradorModel = new Administrador($connect);
            $this->userSession = new ControladorDeSessiones($session,$connect);
            $this->usuarios = new Usuario($connect);
            $this->documentacion = new Documentacion($connect);
        }

        public function home(){
            if($this->userSession->Roll() === ADMIN){
                $this->render('administradorUsuarios', [] ,'admin');
            }else{
                header("Location: ".URL_PATH);
            }
        }


        public function table(){
            $result = new Result();

            $users = $this->usuarios->getAll();

            if($users){
                $result->success = true;
                $result->result = $users;
            }else{
                $result->success = false;
                $result->message = 'no existen usuarios cargados';
            }
            echo json_encode($result);
        }
        //-------------------------------------------------------------------------------//

        //------------------------ Usuarios verificados----------------------------------//

        public function verificar(){

        }
    }
?>