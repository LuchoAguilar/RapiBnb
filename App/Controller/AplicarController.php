<?php 
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/ofertaAlquiler.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/reserva.php');
    require_once(__DIR__.'/../Model/aplicaOferta.php');

    class AplicarController extends Controller{

        private $ofertas;
        private $usuarios;
        private $userSession;
        private $reserva;
        private $aplicaOferta;

        public function __construct($connect,$session){
            $this->ofertas = new OfertaAlquiler($connect);
            $this->usuarios = new Usuario($connect);
            $this->userSession = new ControladorDeSessiones($session,$connect);
            $this->reserva = new Reserva($connect);
            $this->aplicaOferta = new AplicaOferta($connect);
        }

        //------------------------------------------ muestra de informacion de ofertas, aplicaciones y reservas de usuarios----------------------------------//

        public function home(){
            if($this->userSession->Roll() === LOG){
                $this->render('aplicar', [] ,'site');
            }else{
                header("Location: ".URL_PATH);
            }
        }

        public function table() {

            $result = new Result();
            $userID = $this->userSession->ID();
            // Obtener solo las que están publicadas
            $ofertasPublicadasUser = $this->obtenerOfertasPublicadas($userID); 

            // Obtener aplicantes de oferta
            foreach ($ofertasPublicadasUser as $oferPublicada) {
                $aplicantesAlasOfertasUser = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $oferPublicada['ofertaID']);
            }

            // obtener las ofertas publicadas del usuario con sus respectivos aplicantes(puede ser solo ofertas publicadas o el arreglo vacio)
            if(count($ofertasPublicadasUser) > 0 || count($aplicantesAlasOfertasUser) > 0){
                $usuarios = $this->usuarios->getAll();
                $oferta_con_aplicante = $this->ofertas_y_Aplicantes($usuarios, $ofertasPublicadasUser, $aplicantesAlasOfertasUser);
            }
            
            //hay que pasar sus propias aplicaciones. darle a que oferta aplico
            $aplicacionesDelUsuario = $this->aplicaOferta->buscarRegistrosRelacionados('usuarios','usuarioID','usuarioAplicoID',$userID);

            $ofertasAplicadasUsuario = [];
            if(count($aplicacionesDelUsuario) > 0){
                $ofertas = $this->ofertas->getAll();
                foreach($aplicacionesDelUsuario as $aUsuario){
                    foreach ($ofertas as $oferta) {
                        if($aUsuario['ofertaAlquilerID'] === $oferta['ofertaID']){
                            $ofertasAplicadasUsuario[] = [
                                'oferta' => $oferta,
                                'aplicacion' => $aUsuario,
                            ];
                        }
                    }
                }
            }

            // pasar las reservas que hizo el usuario
            $reservasUsuario = $this->reserva->buscarRegistrosRelacionados('usuarios','usuarioID','autorID',$userID);
            $reservas = [];
            if($reservasUsuario){
               $ofertas = $this->ofertas->getAll();
               foreach($reservasUsuario as $reserva){
                    foreach($ofertas as $oferta){
                        if($reserva['ofertaAlquilerID'] === $oferta['ofertaID']){
                            $reservas[] = [
                                'oferta' => $oferta,
                                'reservaUser' => $reserva,
                            ];
                        }
                    }
               }
            }

            // pasar las reservas que le hicieron a las ofertas publicadas del usuario
            $reservasDeOfertas = [];
            if(count($ofertasPublicadasUser) > 0){
                foreach($ofertasPublicadasUser as $ofertaPublicada){
                    $reservasDeOfertas[] = [
                        'reservas' => $this->reserva->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $oferPublicada['ofertaID']),
                        'ofertaUser' => $ofertaPublicada,
                    ];
                }
            }
            
            
        
            // Enviar la data
            $result->success = true;
            $result->result = [
                'ofertasAplicantes' => $oferta_con_aplicante,
                'aplicacionesDelUsuario' => $ofertasAplicadasUsuario,
                'reservasDelUsuario' => $reservas,
                'reservasDeOfertasP' => $reservasDeOfertas,
            ];
        
            echo json_encode($result);
        }

        public function obtenerOfertasPublicadas($idUser){

            $ofertasUser = $this->ofertas->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'creadorID', $idUser);
        
            // Obtener solo las que están publicadas
            $ofertasPublicadas = [];
        
            foreach ($ofertasUser as $ofUser) {
                if ($ofUser['estado'] === PUBLICADO) {
                    $ofertasPublicadas[] = $ofUser;
                }
            }

            return $ofertasPublicadas;
        }

        public function ofertas_y_Aplicantes($usuariosAll, $ofertasPublicadasUser, $aplicantesAlasOfertasUser){
            $oferta_con_aplicante = [];
            if(count($aplicantesAlasOfertasUser) > 0){
                foreach($aplicantesAlasOfertasUser as $aplicante){
                    foreach($usuariosAll as $usuario){
                        if($aplicante['usuarioAplicoID'] === $usuario['usuarioID']){
                            foreach($ofertasPublicadasUser as $oferPublicada){
                                if($aplicante['ofertaAlquilerID'] === $oferPublicada['ofertaID']){
                                    $oferta_con_aplicante[] = [
                                        'ofertaPublicada' => $oferPublicada,
                                        'usuarioAplicante' => $usuario,
                                    ];
                                }
                            }
                        }
                    }
                }
                return $oferta_con_aplicante; // retorna el arreglo de las ofertas publicadas y sus aplicantes

            }elseif(count($ofertasPublicadasUser) > 0){
                foreach($ofertasPublicadasUser as $oferta){
                    
                    $oferta_con_aplicante[] = [
                        'ofertaPublicada' => $oferta,
                        'usuarioAplicante' => [],
                    ];
                }
                return $oferta_con_aplicante; // retorna el arreglo solo las ofertas publicadas

            }else{
                return $oferta_con_aplicante; // retorna el arreglo vacio
            }
            
        }

        //---------------------------------------------------------------------------------------------------------------------------------------------//
        //---------------------------------------------------- Reservas crear y modificar -------------------------------------------------------------//
        public function crearReserva(){
            // entender que una reserva se crea cuando se acepta una aplicacion(en caso de verificado se hace una aplicacion aceptada y de ahi la reserva) por lo que se debera enviar la data sacada de las aplicaciones.
            $result = new Result();
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idOferta = (isset($_POST['ofertaID']))? $_POST['ofertaID']:'';
                $idUsuario = (isset($_POST['usuarioID'])) ? $_POST['usuarioID']:'';
                // debo anular la foranea de oferta en aplicar_oferta
                // para crear la reserva y que no se muestre en la parte de informacion

                if($idOferta != '' && $idUsuario !='' && is_numeric($idUsuario) && is_numeric($idOferta)){
                    //hay que pasar sus propias aplicaciones. darle a que oferta aplico
                    $aplicacionesDelUsuario = $this->aplicaOferta->buscarRegistrosRelacionados('usuarios','usuarioID','usuarioAplicoID',$idUsuario);

                    if($aplicacionesDelUsuario){
                        foreach($aplicacionesDelUsuario as $apliUser){
                            if($apliUser['ofertaAlquilerID'] === $idOferta){
                                $this->aplicaOferta->update($apliUser['aplicacionID'],[
                                    'ofertaAlquilerID' => null,
                                ]);
                                $this->reserva->insert([
                                    'ofertaAlquilerID' => $idOferta,
                                    'autorID' => $idUsuario,
                                    'textoReserva' => '',
                                    'respuesta' => '',
                                    'puntaje' => 0,
                                ]);
                                $result->success = true;
                                $result->message = 'reserva creada con éxito';
                            }
                        }
                        
                    }else{
                        $result->success = false;
                        $result->message = 'Error: usuario sin aplicaciones a publicaciones';
                    }
                    
                    
                }else{
                    $result->success = false;
                    $result->message = 'Error: al traer la informacion';
                }
            }else{
                $result->success = false;
                $result->message = 'Error: Solicitud invalida';
            }
        }

        public function editarReserva(){
            // la data debe venir del user que oferto y logro la reserva.
            // se envia el id del usuario dueño de la oferta directamente para contestar comentarios
            $result = new Result();
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idDueñoOferta = (isset($_POST['ofertaID'])) ? $_POST['ofertaID']:'';
                $idUsuario = (isset($_POST['usuarioID'])) ? $_POST['usuarioID']:'';
                $puntaje = (isset($_POST['puntaje'])) ? $_POST['puntaje']:'';
                $resena = (isset($_POST['resena'])) ? $_POST['resena']:'';
                $contestacion = (isset($_POST['contestacion'])) ? $_POST['contestacion']:'';
                //probablemente va a venir con un puntaje o mensaje
                //obtener la reserva a modificar
                if($idUsuario != null && is_numeric($idUsuario) && $idDueñoOferta === ''){
                    if($puntaje != null && $resena === ''){
                        $this->reserva->update();
                    }//elseif(){}

                }elseif($idDueñoOferta != null && is_numeric($idDueñoOferta) && $idUsuario === ''){

                }else{
                    $result->success = false;
                    $result->message = 'Error: al traer la informacion';
                }

            }else{
                $result->success = false;
                $result->message = 'Error: Solicitud invalida';
            }    
            //si recibe puntaje
            //si recibe mensaje(debe ser de un verificado/ deberia de discriminarse antes para que no pueda escribir siquiera el user no verif)
            //si recibe contestacion
            // en caso de puntuar se edita y resenar solo una vez?
            
        }

        //---------------------------------------------------------------------------------------------------------------------------------------------//

        public function aplicar(){
            
        }

        public function rechazar(){

        }

    }
?>