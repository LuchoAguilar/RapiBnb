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
            $usuarios = $this->usuarios->getAll();
        
            // Obtener ofertas de alquiler del usuario
            $ofertasUser = $this->ofertas->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'creadorID', $userID);
        
            // Obtener solo las que están publicadas
            $ofertasPublicadas = [];
        
            foreach ($ofertasUser as $ofUser) {
                if ($ofUser['estado'] === PUBLICADO) {
                    $ofertasPublicadas[] = $ofUser;
                }
            }
        
            // Obtener aplicantes de oferta
            foreach ($ofertasPublicadas as $oferPublicada) {
                $aplicantesOferta = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $oferPublicada['ofertaID']);
            }

            $oferta_con_aplicante = [];
            if(count($aplicantesOferta) > 0){
                foreach($aplicantesOferta as $aplicante){
                    foreach($usuarios as $usuario){
                        if($aplicante['usuarioAplicoID'] === $usuario['usuarioID']){
                            foreach($ofertasPublicadas as $oferPublicada){
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
            }elseif(count($ofertasPublicadas) > 0){
                foreach($ofertasPublicadas as $oferta){
                    
                    $oferta_con_aplicante[] = [
                        'ofertaPublicada' => $oferta,
                        'usuarioAplicante' => [],
                    ];
                }
    
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
            
        
            // Enviar la data
            $result->success = true;
            $result->result = [
                'ofertasAplicantes' => $oferta_con_aplicante,
                'aplicacionesDelUsuario' => $ofertasAplicadasUsuario,
            ];
        
            echo json_encode($result);
        }
        

        public function crearReserva(){

        }

        public function aplicar(){

        }


    }
?>