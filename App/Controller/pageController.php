<?php
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/reserva.php');
    require_once(__DIR__.'/../Model/ofertaAlquiler.php');
    require_once(__DIR__.'/../Model/intereses.php');

    class PageController extends Controller{

        private $userSessionControl;
        private $ofertas;
        private $usuarios;
        private $reservas;
        private $intereses;

        public function __construct($connect,$session){
            $this->userSessionControl = new ControladorDeSessiones($session,$connect);
            $this->ofertas = new OfertaAlquiler($connect);
            $this->usuarios = new Usuario($connect);
            $this->reservas = new Reserva($connect);
            $this->intereses = new Intereses($connect);
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
        //--------------------------------------------Mostrar data----------------------------------------------------------------//

        /** 
         * las ofertas se deben mostrar en cards, que deben mostrar:
         * la oferta de manera resumida : fotos,titulo,ubicacion.
         * al darle click a la oferta se debe abrir un modal pantalla completa/o page nueva, que muestre el resto de la data de la oferta y las reseñas y respuestas hechas(si las tiene) y quienes la hicieron(foto perfil y nombre).
         * se va a trabaja en la page home tanto para usuarios logeados y para los no logeados.
        */
        public function listarOfertas(){
            //tienen que ser solo con estado:publicado y de users normales.
            $result = new Result();

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $page = (isset($_POST['pageNumber']))?$_POST['pageNumber']:1;

                $consulta = "WHERE estado = 'publicado' AND userVerificado = 'no' ";
                $ofertasPublicadas = $this->ofertas->paginationWithConditions($page,4,'*',$consulta);
                $result->success = true;
                $result->result = $ofertasPublicadas;
                $result->message = $page;
            }else{
                $page = 1;
                $consulta = "WHERE estado = 'publicado' AND userVerificado = 'no' ";
                $ofertasPublicadas = $this->ofertas->paginationWithConditions($page,4,'*',$consulta);
                $result->success = true;
                $result->result = $ofertasPublicadas;
                $result->message = $page;
            }
            echo json_encode($result);
        }



        public function listarOfertasVerificados(){
            // son las ofertas que deben aparecer de manera destacada
            $result = new Result();
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $page = (isset($_POST['pageNumber']))? $_POST['pageNumber'] : 1;

                $consulta = "WHERE estado = 'publicado' AND userVerificado = 'si' ";
                $ofertasPublicadas = $this->ofertas->paginationWithConditions($page,4,'*',$consulta);
                $result->success = true;
                $result->result = $ofertasPublicadas;
                $result->message = $page;
            }else{
                $consulta = "WHERE estado = 'publicado' AND userVerificado = 'si' ";
                $page = 1;
                $ofertasPublicadas = $this->ofertas->paginationWithConditions($page,4,'*',$consulta);
                $result->success = true;
                $result->result = $ofertasPublicadas;
            }
            
            echo json_encode($result);
        }

        public function listarRecomentaciones(){
            // son las que salen del pareo de intereses(usuario) y etiquetas(ofertas alquiler)
            $result = new Result();
            $userID = $this->userSessionControl->ID();
            $user = $this->usuarios->getById($userID);
            $interesesUser = $this->intereses->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'userInteresesID', $userID);
            $intereses = '';
            if($interesesUser){    
                
                foreach ($interesesUser as $interes) {
                    $ubicaciones = $interes['ubicacion'];
                    $etiquetas = $interes['etiquetas'];
                    $servicios = $interes['listServicios'];
                }

                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $page = (isset($_POST['pageNumber']))? $_POST['pageNumber'] : 1;
                    $consulta = 'WHERE ';// Inicializa la consulta como una cadena vacía
                    $condiciones = []; // Inicializa un array para las condiciones

                    if (!empty($ubicaciones)) {
                        $ubicaciones = !empty($ubicaciones) ? explode('- ', $ubicaciones) : [];
                        $ubicaciones = array_map(function ($ubicacion) {
                            return "'" . $ubicacion . "'";
                        }, $ubicaciones);
                        $condiciones[] = 'ubicacion IN (' . implode('- ', $ubicaciones) . ')';
                    }

                    if (!empty($etiquetas)) {
                        $etiquetas = !empty($etiquetas) ? explode(', ', $etiquetas) : [];
                        $etiquetas = array_map(function ($etiqueta) {
                            return "'" . $etiqueta . "'";
                        }, $etiquetas);
                        $condiciones[] = 'etiquetas IN (' . implode(', ', $etiquetas) . ')';
                    }

                    if (!empty($servicios)) {
                        $servicios = !empty($servicios) ? explode(', ', $servicios) : [];
                        $servicios = array_map(function ($servicio) {
                            return "'" . $servicio . "'";
                        }, $servicios);
                        $condiciones[] = 'listServicios IN (' . implode(', ', $servicios) . ')';
                    }

                    if (!empty($condiciones)) {
                        $consulta .= implode(' OR ', $condiciones);
                    }

                    $ofertasPublicadas = $this->ofertas->paginationWithConditions($page, 4, '*', $consulta);

                    

                    $result->success = true;
                    $result->result = $ofertasPublicadas;
                    $result->message = $page;
                }else{
    
                    $consulta = 'WHERE '; // Inicializa la consulta como una cadena vacía
                    $condiciones = []; // Inicializa un array para las condiciones

                    
                    if (!empty($ubicaciones)) {
                        $ubicaciones = !empty($ubicaciones) ? explode('- ', $ubicaciones) : [];
                        $ubicaciones = array_map(function ($ubicacion) {
                            return "'" . $ubicacion . "'";
                        }, $ubicaciones);
                        $condiciones[] = 'ubicacion IN (' . implode('- ', $ubicaciones) . ')';
                    }
                    
                    if (!empty($etiquetas)) {
                        $etiquetas = !empty($etiquetas) ? explode(', ', $etiquetas) : [];
                        $etiquetas = array_map(function ($etiqueta) {
                            return "'" . $etiqueta . "'";
                        }, $etiquetas);
                        $condiciones[] = 'etiquetas IN (' . implode(', ', $etiquetas) . ')';
                    }
                    
                    if (!empty($servicios)) {
                        $servicios = !empty($servicios) ? explode(', ', $servicios) : [];
                        $servicios = array_map(function ($servicio) {
                            return "'" . $servicio . "'";
                        }, $servicios);
                        $condiciones[] = 'listServicios IN (' . implode(', ', $servicios) . ')';
                    }
                    
                    if (!empty($condiciones)) {
                        $consulta .= implode(' OR ', $condiciones);
                    } 

                    $page = 1;
                    $ofertasPublicadas = $this->ofertas->paginationWithConditions($page, 4, '*', $consulta);
                    $result->success = true;
                    $result->result = $ofertasPublicadas;
                }
            }else{
                $result->success = false;
                $result->message = $interesesUser;
            }
            
            
            
            echo json_encode($result);
        }
        //---------------------------------------------------------------------------------------------------------------------------//
        //------------------------------------------------Busquedas por palabras y por etiquetas--------------------------------------------------//

        public function buscar(){
            if($this->userSessionControl->Roll() === NO_LOG){
                $this->render('Buscador',[],'noLog');
            }elseif($this->userSessionControl->Roll() === LOG){
                $this->render('Buscador',[],'site');
            }
        }



        //---------------------------------------------------------------------------------------------------------------------------//
    }
?>