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
        public function crear(){
            if($this->userSession->Roll() === 'usuarioLog'){
                $this->render('publicarOfertaCreate',[],'site');
            }else{
                header("Location: http://localhost/PM/Public/");
            } 
        }

        public function create(){
            $result = new Result();
            
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $titulo = (isset($_POST['titulo'])) ? $_POST['titulo']: '';
                $descripcion = (isset($_POST['descripcion'])) ? $_POST['descripcion']: '';
                $ubicacion = (isset($_POST['ubicacion'])) ? $_POST['ubicacion']: '';
                $etiqueta = (isset($_POST['etiqueta'])) ? $_POST['etiqueta']: '';
                $costo = (isset($_POST['costoAlquilerPorDia'])) ? $_POST['costoAlquilerPorDia']: '';
                $cupo = (isset($_POST['cupo']))? $_POST['cupo']: '';
                $tiempoMin = (isset($_POST['tiempoMinPermanencia'])) ? $_POST['tiempoMinPermanencia']: '';
                $tiempoMax = (isset($_POST['tiempoMaxPermanencia'])) ? $_POST['tiempoMaxPermanencia']: '';
                $fechaIni = (isset($_POST['fechaInicio'])) ? $_POST['fechaInicio']: '';
                $fechaFin = (isset($_POST['fechaFin'])) ? $_POST['fechaFin']: '';
                $listServicios = isset($_POST['listServicios']) ? implode(", ", $_POST['listServicios']) : '';
                
                if (isset($_FILES['galeriaFotos']) && is_array($_FILES['galeriaFotos']['tmp_name'])) {
                    $galeriaFotos = [];
                
                    foreach ($_FILES['galeriaFotos']['tmp_name'] as $key => $tmp_name) {
                        // Validar si es una imagen y obtener la extensión del archivo
                        $tipoMIME = $_FILES['galeriaFotos']['type'][$key];
                        $extension = pathinfo($_FILES['galeriaFotos']['name'][$key], PATHINFO_EXTENSION);
                        
                        // Verificar que es una imagen válida 
                        if (strpos($tipoMIME, 'image') === 0) {
                            // Generar un nombre de archivo único con una extensión segura
                            $fecha_img = new DateTime();
                            $nombre_foto = $fecha_img->getTimestamp() . '_' . uniqid() . '.' . $extension;
                            
                            // Ruta de destino relativa al directorio de imágenes
                            //'C:\xampp\htdocs\PM\Public\Assets\images\galeriaFotos\\'
                            $destinationPath = 'Assets/images/galeriaFotos/' . $nombre_foto;
                            
                            // Mover la imagen al directorio de destino
                            if (move_uploaded_file($tmp_name, $destinationPath)) {
                                $galeriaFotos[] = $nombre_foto;
                            }
                        }
                    }
                }

                $fotosString = implode(", ", $galeriaFotos);
            }

            $id = $this->userSession->ID();

            $this->ofertaModel->insert([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'ubicacion' => $ubicacion,
                'etiquetas' =>  $etiqueta,
                'galeriaFotos' => $fotosString,
                'listServicios' => $listServicios,
                'costoAlquilerPorDia' => $costo,
                'tiempoMinPermanencia' => $tiempoMin,
                'tiempoMaxPermanencia' => $tiempoMax,
                'cupo' => $cupo,
                'fechaInicio' => $fechaIni,
                'fechaFin' => $fechaFin,
                'creadorID' => $id
            ]);

            $result->success = true;
            $result->message = "Oferta de alquiler creada con éxito";
            echo json_encode($result);
        }

        public function edit(){

        }

        public function delete(){

        }

        public function table(){
            $result = new Result();
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