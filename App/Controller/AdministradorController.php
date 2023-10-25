<?php
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/administrador.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/documentacion.php');
    require_once(__DIR__.'/../Model/verificacion.php');

    class AdministradorController extends Controller{
        
        private $administradorModel;
        private $userSession;
        private $usuarios;
        private $documentacion;
        private $verificacion;

        public function __construct($connect,$session){
            $this->administradorModel = new Administrador($connect);
            $this->userSession = new ControladorDeSessiones($session,$connect);
            $this->usuarios = new Usuario($connect);
            $this->documentacion = new Documentacion($connect);
            $this->verificacion = new Verificacion($connect);
        }

        public function home(){
            if($this->userSession->Roll() === ADMIN){
                $this->render('administradorUsuarios', [] ,'admin');
            }else{
                header("Location: ".URL_PATH);
            }
        }


        public function table() {
            $result = new Result();
            $users = $this->usuarios->getAll();
        
            if ($users) {
                $result->success = true;
                $result->result = [];
        
                foreach ($users as $user) {
                    // Inicializa $isVerificado como false para este usuario
                    $isVerificado = false;
        
                    // Obtiene la lista de usuarios verificados relacionados con este usuario
                    $verificados = $this->usuarios->buscarRegistrosRelacionados('verificacion_cuenta', 'verificacionID', 'usuarioID', $user['usuarioID']);
        
                    // Verifica si el usuario está en la lista de usuarios verificados
                    foreach ($verificados as $userVerificado) {
                        if ($user['usuarioID'] == $userVerificado['usuarioID']) {
                            $isVerificado = true;
                            break;
                        }
                    }
        
                    $result->result[] = [
                        'usuario' => $user,
                        'verificado' => $isVerificado,
                    ];
                }
            } else {
                $result->success = false;
                $result->message = 'No existen usuarios cargados.';
            }
        
            // Devuelve los datos como JSON
            header('Content-Type: application/json');
            echo json_encode($result);
        }
        
        //-------------------------------------------------------------------------------//


        //------------------------ Usuarios verificados----------------------------------//

        public function verificaciones(){
            if($this->userSession->Roll() === ADMIN){
                $this->render('administradorVerificados', [] ,'admin');
            }else{
                header("Location: ".URL_PATH);
            }
        }

        public function postulantes() {
            $result = new Result();
            $users = $this->usuarios->getAll();
        
            if ($users) {
                foreach($users as $user){
                    $documentacion = $this->documentacion->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usarioAVerfID', $user['usuarioID']);
                }
                
                if($documentacion){
                    $result->success = true;
                    $result->result = [];
                    foreach($documentacion as $doc){
                        foreach($users as $user){
                            if($doc['usarioAVerfID'] === $user['usuarioID']){
                                $result->result[] = [
                                    'postulante' => $user,
                                    'documentacion' => $doc,
                                ];
                            }
                        }
                        
                    }
                }else{
                    $result->success = false;
                    $result->message = 'No existen postulantes a verificar.';
                }
            } else {
                $result->success = false;
                $result->message = 'No existen usuarios cargados.';
            }
        
            // Devuelve los datos como JSON
            header('Content-Type: application/json');
            echo json_encode($result);
        }
        

       //-------------------------------------------------------------------------------//
        //------------------------eliminar postulación----------------------------------//

        public function borrarPostulacion(){
            $result = new Result();

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $idPostulante = (isset($_POST['id'])) ? $_POST['id'] : '';

                if ($idPostulante != '') {

                    if ($this->delete($idPostulante)) {
                        $result->success = true;
                        $result->message = 'Postulación eliminada con éxito.';
                    } else {
                        $result->success = false;
                        $result->message = 'Error al borrar la postulacion.';
                    }
                } else {
                    $result->success = false;
                    $result->message = "Error al traer el ID.";
                }
            } else {
                $result->success = false;
                $result->message = "Solicitud inválida.";
            }

            // Devuelve los resultados como JSON
            header('Content-Type: application/json');
            echo json_encode($result);
        }

        public function delete($idPostulante){
            $user = $this->usuario->getById($idPostulante);
            if ($user) {
                $documentacion = $this->documentacion->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usarioAVerfID', $idPostulante);

                foreach ($documentacion as $doc) {
                    $documentacionPath = 'Assets/images/documentacion/' . $doc['documentoAdjunto'];

                    if (file_exists($documentacionPath)) {
                        unlink($documentacionPath);
                    }

                    $this->documentacion->deleteById($doc['certificacionID']);
                }

                $this->usuario->updateById($user['usuarioID'], [
                    'documentacionID' => null,
                ]);

                return true;
            } else {
                return false;
            }                   
        }

        //---------------------------------------------------------------------------------------------------------------------//
        //-----------------------------------------------Verificar Cuenta---------------------------------------------------//

        public function verificar(){
            $result = new Result();
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $idPostulante = (isset($_POST['id'])) ? $_POST['id'] : '';
                if($idPostulante){
                    if($this->delete($idPostulante)){
                        $fechaVencimiento = new DateTime();
                        $fechaVencimiento->modify('+30 days');
                        $this->verificacion->upsert($idPostulante,[
                            'fechaVencimiento' => $fechaVencimiento,
                            'usuarioPropuestaID' => $idPostulante,
                        ]);

                        $result-> success = true;
                        $result->message = 'Verificacion creada correctamente';
                    }else{
                        $result->success = false;
                        $result->message = "Error al borrar la postulacion.";
                    }
                    
                }else{
                    $result->success = false;
                    $result->message = "Error al traer el ID.";
                }
            else{
                $result->success = false;
                $result->message = "Solicitud inválida.";
            }
            // Devuelve los resultados como JSON
            header('Content-Type: application/json');
            echo json_encode($result);    
        }

        //---------------------------------------------------------------------------------------------------------------------//

    }
?>