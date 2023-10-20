<?php 
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/usuario.php');

    class UsuarioController extends Controller{

        private $usuarioModel;
        private $userSession;

        public function __construct($connect, $session){
            $this->usuarioModel = new Usuario($connect);
            $this->userSessionControl = new ControladorDeSessiones($session,$connect);
        }

        public function home(){
            if($this->userSessionControl->Roll() === 'usuarioLog'){
                $this->render('usuario', [] ,'site');
            }else{
                header("Location: http://localhost/PM/Public/");
            }
            
        }

        public function signUp(){
            $this->render("UsuarioSignUp", [], "login");
        }

        public function login(){
            $this->render('usuarioLog', [] , "login");
        }

        public function LogOut(){
            $this->userSessionControl->cerrarSesion();
            $this->home();
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
                        $result->message = "Error desconocido.";
                }
            }
            
            echo json_encode($result);
        }
        



        public function table(){
            $result = new Result();
            $usuarios = $this->usuarioModel->getAll();
            $result->success = true;
            $result->result = $usuarios;
            echo json_encode($result);
        }

        public function create(){
            $result = new Result();

           $this->usuarioModel->insert([
            'nombreUsuario' => $_POST['usuario'],
            'correo' => $_POST['correo'],
            'contrasena' => password_hash($_POST['password'],PASSWORD_BCRYPT),
           ]);
           $result->success = true;
           $result->message = "El registro fue cargado con éxito";

           echo json_encode($result);
        }

        public function edit(){
            if($this->userSessionControl->Roll() === 'usuarioLog'){
                $id = $_GET['id'];

                $usuario = $this->usuarioModel->getById($id);

                $this->render('usuarioEdit',[
                    'usuario' => $usuario,
                ],'site');
            }else{
                header("Location: http://localhost/PM/Public/");
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
                                $fotoRostroPath = URL_PATH . "/Assets/images/fotoPerfil/" . $usuario['fotoRostro'];
        
                                if (file_exists($fotoRostroPath)) {
                                    unlink($fotoRostroPath);
                                }
                            }
        
                            // Mover la nueva imagen a la carpeta de imágenes
                            $fecha_img = new DateTime();
                            $nombre_foto = $fecha_img->getTimestamp() . "_" . $foto;
                            $img_tmp = $_FILES['fotoPerfil']['tmp_name'];
        
                            if ($img_tmp !== "") {
                                $destinationPath = 'C:\xampp\htdocs\PM\Public\Assets\images\fotoPerfil\\' . $nombre_foto;
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
        

        public function delete() {
            $result = new Result();
        
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $idUsuario = (isset($_POST['id'])) ? $_POST['id'] : null;
        
                if ($idUsuario !== null && is_numeric($idUsuario)) {
                    $usuario = $this->usuarioModel->getById($idUsuario);
        
                    if ($usuario) {
                        // Eliminar al usuario en la base de datos
                        $this->usuarioModel->deleteById($idUsuario);
        
                        // Luego, eliminar la foto de perfil si existe
                        if (isset($usuario['fotoRostro'])) {
                            $fotoRostroPath = URL_PATH . "/Assets/images/fotoPerfil/" . $usuario['fotoRostro'];
        
                            if (file_exists($fotoRostroPath)) {
                                unlink($fotoRostroPath);
                            }
                        }
        
                        $result->success = true;
                        $result->message = "Usuario eliminado con éxito";
                    } else {
                        $result->message = "Usuario no encontrado";
                    }
                } else {
                    $result->message = "ID de usuario no válido";
                }
            } else {
                $result->message = "Solicitud no válida";
            }
            // recordar verificar que se eliminen todos los datos relacionados de manera foranea en otras tablas
        
            echo json_encode($result);
        }
        

        public function validaciones(){
            // debe ser general
        }
        

    }    
?>