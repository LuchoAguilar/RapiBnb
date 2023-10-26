<?php 
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/intereses.php');
    require_once(__DIR__.'/../Model/documentacion.php');
    require_once(__DIR__.'/../Model/administrador.php');

    class UsuarioController extends Controller{

        private $usuarioModel;
        private $userSession;
        private $intereses;
        private $documentacion;

        public function __construct($connect, $session){
            $this->usuarioModel = new Usuario($connect);
            $this->intereses = new Intereses($connect);
            $this->documentacion = new documentacion($connect);
            $this->userSessionControl = new ControladorDeSessiones($session,$connect);
        }

        //---------------------------------------------muestra de perfil de usuario--------------------------------------------------//

        public function home(){
            if($this->userSessionControl->Roll() === LOG){
                $this->render('usuario', [] ,'site');
            }else{
                header("Location: ".URL_PATH);
            }
            
        }

        public function table(){
            
            $result = new Result();
            $user = $this->userSessionControl->ID();
            $userVerificado = $this->userSessionControl->esVerificado();
            $usuario = $this->usuarioModel->getById($user);
            $intereses = $this->intereses->buscarRegistrosRelacionados('usuarios','usuarioID','userInteresesID',$user);
            // toca pasar el parametro de verificado
            if(empty($intereses)){
                $result->success = true;
                $result->result = [
                    'usuario' => $usuario,
                    'esVerificado' => $userVerificado,
                ];
            }else{
                $result->success = true;
                $result->result = [
                    'usuario' => $usuario,
                    'intereses' => $intereses,
                    'esVerificado' => $userVerificado,
                ];
            } 
            echo json_encode($result);
        }

        //---------------------------------------------------------------------------------------------------------------------//

        //---------------------------------------------registro usuarios--------------------------------------------------//

        public function signUp(){
            if($this->userSessionControl->Roll() === NO_LOG){
                $this->render("UsuarioSignUp", [], "login");
            }else{
                header("Location: ".URL_PATH);
            }    
        }

        public function create() {
            $result = new Result();
        
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
                $correo = isset($_POST['correo']) ? $_POST['correo'] : '';
                $contrasena = isset($_POST['password']) ? $_POST['password'] : '';
        
                // Validación adicional (puedes personalizarla según tus requisitos)
                if (empty($usuario) || empty($correo) || empty($contrasena) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $result->success = false;
                    $result->message = "Datos de registro no válidos.";
                } else {
                    // Verificar si el usuario o el correo ya existen
                    $existsUser = $this->usuarioModel->exists('nombreUsuario', $usuario);
                    $existsCorreo = $this->usuarioModel->exists('correo', $correo);
        
                    if (!$existsUser && !$existsCorreo) {

                        try{
                            $this->usuarioModel->insert([
                                'nombreUsuario' => $usuario,
                                'correo' => $correo,
                                'contrasena' => password_hash($contrasena ,PASSWORD_BCRYPT)
                            ]);
                            $mensaje = $this->userSessionControl->inicioSesion($usuario, $contrasena);
                            switch ($mensaje) {
                                    case "Sesión iniciada correctamente.":
                                        $result->success = true;
                                        $result->message = "Cuenta y Sesión Realizada con Éxito.";
                                        break;
                                    case "Error: El usuario no fue encontrado.":
                                        $result->success = false;
                                        $result->message = "El usuario no fue encontrado.";
                                        break;
                                    case "Error: Contraseña incorrecta.":
                                        $result->success = false;
                                        $result->message = "Contraseña incorrecta.";
                                        break;
                                    default:
                                        $result->success = false;
                                        $result->message = "Error desconocido: $mensaje"; // Agrega el mensaje real
                                }  
                        }catch(Exception $error){
                            $result->success = false;
                            $result->message = $error;
                        }

                    } else {
                        $result->success = false;
                        $result->message = "El nombre de usuario o correo electrónico ya existen.";
                    }

                }
                
            }else{
                $result->success = false;
                $result->message = "Solicitud invalida.";
            } 
           echo json_encode($result);
        }
        //---------------------------------------------------------------------------------------------------------------------//

        //---------------------------------------------inicio sesion--------------------------------------------------//

        public function login(){
            if($this->userSessionControl->Roll() === NO_LOG){
                $this->render('usuarioLog', [] , "login");
            }else{
                header("Location: ".URL_PATH);
            }
            
        }

        public function loginProcess() {

            $result = new Result();
            
            // Comprobar si los datos requeridos están presentes
            if (!isset($_POST['usuario']) || !isset($_POST['contrasena'])) {
                $result->success = false;
                $result->message = "Faltan datos requeridos.";
            } else {
                $mensaje = $this->userSessionControl->inicioSesion($_POST['usuario'], $_POST['contrasena']);
                switch ($mensaje) {
                    case "Sesión iniciada correctamente.":
                        $result->success = true;
                        $result->message = "Sesión iniciada correctamente.";
                        break;
                    case "Error: El usuario no fue encontrado.":
                        $result->success = false;
                        $result->message = "El usuario no fue encontrado.";
                        break;
                    case "Error: Contraseña incorrecta.":
                        $result->success = false;
                        $result->message = "Contraseña incorrecta.";
                        break;
                    default:
                        $result->success = false;
                        $result->message = "Error desconocido: $mensaje"; // Agrega el mensaje real
                }
            }
            
            echo json_encode($result);
        }
        //---------------------------------------------------------------------------------------------------------------------//

        //---------------------------------------------cerrar sesion--------------------------------------------------//

        public function LogOut(){
            if($this->userSessionControl->Roll() === LOG || $this->userSessionControl->Roll() === ADMIN ){
                $this->userSessionControl->cerrarSesion();
                $this->home();
            }else{
                header("Location: ".URL_PATH);
            }   
        }

        

        //---------------------------------------------------------------------------------------------------------------------//

        //---------------------------------------------editar perfil--------------------------------------------------//

        public function edit(){
            if($this->userSessionControl->Roll() === LOG){
                $id = $_GET['id'];

                $usuario = $this->usuarioModel->getById($id);

                $this->render('usuarioEdit',[
                    'usuario' => $usuario,
                ],'site');
            }else{
                header("Location:".URL_PATH);
            } 
        }

        public function update() {
            $result = new Result();
        
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $idUsuario = (isset($_POST['textID'])) ? $_POST['textID'] : null;
                $nombre = (isset($_POST['usuario'])) ? $_POST['usuario'] : null;
                $correo = (isset($_POST['correo'])) ? $_POST['correo'] : null;
                $nombreCompleto = (isset($_POST['nombre'])) ? $_POST['nombre'] : null;
                $bio = (isset($_POST['bio'])) ? $_POST['bio'] : null;
        
                if ($idUsuario !== null && is_numeric($idUsuario)) {
                    $usuario = $this->usuarioModel->getById($idUsuario);
        
                    if ($usuario) {
                        // Verificar si se subió una nueva foto y eliminar la foto anterior si existe
                        $foto = (isset($_FILES['fotoPerfil']['name'])) ? $_FILES['fotoPerfil']['name'] : "";
                        if ($foto !== "") {
                            if (isset($usuario['fotoRostro'])) {
                                $fotoRostroPath =  'Assets/images/fotoPerfil/' . $usuario['fotoRostro'];
        
                                if (file_exists($fotoRostroPath)) {
                                    unlink($fotoRostroPath);
                                }
                            }
        
                            // Mover la nueva imagen a la carpeta de imágenes
                            $fecha_img = new DateTime();
                            $nombre_foto = $fecha_img->getTimestamp() . "_" . $foto;
                            $img_tmp = $_FILES['fotoPerfil']['tmp_name'];
        
                            if ($img_tmp !== "") {
                                $destinationPath = 'Assets/images/fotoPerfil/' . $nombre_foto;
                                move_uploaded_file($img_tmp, $destinationPath);
                            }
        
                            // Actualizar los datos del usuario con la nueva foto
                            $this->usuarioModel->updateById($idUsuario, [
                                'nombreUsuario' => $nombre,
                                'correo' => $correo,
                                'nombreCompleto' => $nombreCompleto,
                                'fotoRostro' => $nombre_foto,
                                'bio' => $bio,
                            ]);
                        } else {
                            // No se subió una nueva foto, actualizar sin cambios en la foto
                            $this->usuarioModel->updateById($idUsuario, [
                                'nombreUsuario' => $nombre,
                                'correo' => $correo,
                                'nombreCompleto' => $nombreCompleto,
                                'bio' => $bio,
                            ]);
                        }
        
                        $result->success = true;
                        $result->message = "Usuario modificado con éxito";
                    } else {
                        $result->message = "Usuario no encontrado";
                    }
                } else {
                    $result->message = "ID de usuario no válido";
                }
            } else {
                $result->message = "Solicitud no válida";
            }
        
            echo json_encode($result);
        }
        //---------------------------------------------------------------------------------------------------------------------//

        //---------------------------------------------intereses del usuario(teniendo en cuenta las etiquetas)--------------------------------------------------//

        public function interesesForm(){
            if($this->userSessionControl->Roll() === LOG){
                $user = $this->userSessionControl->ID();
                $interesPrev = $this->intereses->buscarRegistrosRelacionados('usuarios','usuarioID','userInteresesID',$user);
                $interesID = '';
                foreach($interesPrev as $interes){
                    $interesID = $interes['interesID'];
                }
                $this->render('usuarioIntereses', [
                    'intereses' => $interesID,
                ] , "site");
            }else{
                header("Location: ".URL_PATH);
            }  
        }

        public function intereses(){
            $result = new Result();
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // traemos la data del front
                $user = $this->userSessionControl->ID();
                if (!$user) {
                    $result->success = false;
                    $result->message = "Error de sesión";
                    echo json_encode($result);
                    return;
                }

                $id = (isset($_POST['textID'])) ? $_POST['textID']: '';

                $ubicacion = (isset($_POST['ubicacion'])) ? implode(", ", $_POST['ubicacion']) : '';
                $etiqueta = (isset($_POST['etiqueta'])) ? implode(", ", $_POST['etiqueta']) : '';
                $listServicios = isset($_POST['servicios']) ? implode(", ", $_POST['servicios']) : '';

                $datosCombinados = "$ubicacion | $etiqueta | $listServicios";

                // Verifica si los datos combinados no están vacíos antes de actualizar o insertar en la base de datos
                if (!empty($datosCombinados)) {
                    $this->intereses->upsert($id, [
                        'nombresDeInteres' => $datosCombinados,
                        'userInteresesID' => $user
                    ]);
                    $result->success = true;
                    $result->message = "Datos cargados con éxito";
                } else {
                    $result->success = false;
                    $result->message = "Datos combinados vacíos. Por favor, seleccione al menos una opción.";
                }
            } else {
                $result->success = false;
                $result->message = "Solicitud no válida";
            }
            echo json_encode($result);
        }
        //---------------------------------------------------------------------------------------------------------------------//

        //---------------------------------------------Envio de documentacion para verificar cuenta--------------------------------------------------//

        public function verificar(){
            //que no este verificado previamente y que no este en proceso
            if($this->userSessionControl->Roll() === LOG){
                $user = $this->userSessionControl->ID();
                $documentacion = $this->documentacion->buscarRegistrosRelacionados('usuarios','usuarioID','usarioAVerfID',$user);
                $documentacionID = '';
                foreach($documentacion as $doc){
                    $documentacionID = $doc['certificacionID'];
                }
                if(empty($documentacion)/* && empty($verificado)*/){
                    $this->render('usuarioDocumentacion',[
                        'doc' => $documentacionID,
                    ],'site');
                    
                }else{
                    header("Location:".URL_PATH.'/usuario/home');
                }
            }else{
                header("Location:".URL_PATH);
            } 
        }
        
        public function documentacion(){
            $result = new Result();
            
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $user = $this->userSessionControl->ID();
                
                if (!$user) {
                    $result->success = false;
                    $result->message = "Error de sesión";
                } else {
                    $id = $_POST['textID'] ?? '';
                    $documentacion = $this->documentacion->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usarioAVerfID', $user);
                    $foto = $_FILES['documentacion']['name'] ?? "";
        
                    // Función para mover la imagen a la carpeta de documentos
                    function moveDocumento($newName) {
                        $img_tmp = $_FILES['documentacion']['tmp_name'];
                        $destinationPath = 'Assets/images/documentacion/' . $newName;
                        move_uploaded_file($img_tmp, $destinationPath);
                    }
        
                    $fecha_img = new DateTime();
                    $nombre_foto = $fecha_img->getTimestamp() . "_$foto";
                    $fechaVencimiento = new DateTime();
                    $fechaVencimiento->modify('+2 days');
                    $fechaFormateada = $fechaVencimiento->format('Y-m-d H:i:s');
        
                    if (empty($documentacion)) {
                        moveDocumento($nombre_foto);
                        $this->documentacion->insert([
                            'documentoAdjunto' => $nombre_foto,
                            'fechaDeVencimiento' => $fechaFormateada,
                            'usarioAVerfID' => $user,
                        ]);
                        $docs = $this->documentacion->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usarioAVerfID', $user);
                        $idDoc = '';
                        foreach($docs as $doc){
                            $idDoc = $doc['certificacionID'];
                        }
                        $this->usuarioModel->updateById($user,[
                            'documentacionID' => $idDoc,
                        ]);
                    }
                    
                    $result->success = true;
                    $result->message = "Documento guardado exitosamente.";
                }
            } else {
                $result->success = false;
                $result->message = "Solicitud no válida";
            }
            
            echo json_encode($result);
        }
        
        //---------------------------------------------------------------------------------------------------------------------//

    }     
?>