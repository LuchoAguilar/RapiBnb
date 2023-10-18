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

        public function update(){
            $result = new Result();
            if (empty($_POST['textID'])) {
                $result->success = false;
                $result->message = "Faltan datos requeridos.";
            }else{
                $foto = (isset($_FILES['fotoPerfil']['name'])) ? $_FILES['fotoPerfil']['name'] : "";

                if(!$foto != ""){
                    $usuarioImage = $this->usuarioModel->getById($_POST['textID']);
                    if(isset($usuarioImage['fotoRostro'])){
                        if (file_exists(URL_PATH . "/Assets/images/fotoPerfil/" . $usuarioImage['fotoRostro'])) {
                            unlink(URL_PATH . "/Assets/images/fotoPerfil/" . $usuarioImage['fotoRostro']);
                        }
                    }
                }
                
                //conseguimos la fecha en que se sube la foto
                $fecha_img = new DateTime();
                //y se lo agregamos al nombre de la imagen para que no se repitan si hay mucho volumen de subida y tienen mismo nombre.
                $nombre_foto = ($foto !="")?$fecha_img->getTimesTamp()."_".$foto:"";
    
                //mover imagena carpeta de imagenes
                $img_tmp = $_FILES['fotoPerfil']['tmp_name'];;
                if($img_tmp !=""){
                    $destinationPath = 'C:\xampp\htdocs\PM\Public\Assets\images\fotoPerfil\\' . $nombre_foto;
                    move_uploaded_file($img_tmp, $destinationPath);
                }
            

                $this->usuarioModel->updateById($_POST['textID'],[
                    'nombreUsuario' => $_POST['usuario'],
                    'correo' => $_POST['correo'],
                    'nombreCompleto' => $_POST['nombre'],
                    'fotoRostro' => $nombre_foto,
                    'bio' => $_POST['bio'],
                ]);

                $result->success = true;
                $result->message = "usuario modificado";
            }
            

            echo json_encode($result);
        }

        public function delete() {
            $result = new Result();

            // utilizar el id obtenido por sesion
    
            $this->usuarioModel->deleteById($_POST['id']);
            $result->success = true;
            $result->message = "Usuario eliminado con éxito";

            echo json_encode($result);
        }

        public function validaciones(){
            // debe ser general
        }
        

    }    
?>