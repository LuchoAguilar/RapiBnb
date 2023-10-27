<?php 
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
            
        }

        public function table(){
            $result = new Result();
            $userID = $this->userSession->ID();
            //obtener ofertas de alquiler del usuario
            $ofertasUser = $this->ofertas->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'creadorID' , $userID);
            //obtener solo las que estan publicadas
            $ofertasPublicadas = [];
            foreach($ofertasUser as $ofUser){
                if($ofUser['estado'] === PUBLICADO){
                    $ofertasPublicadas[] = $ofUser;
                }
            }
            //obtener aplicantes de oferta
            $aplicantesOferta = [];
            foreach($ofertasPublicadas as $oferPublicada){
                $aplicantesOferta[] = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler','ofertaID','ofertaAlquilerID',$oferPublicada['ofertaID']);
            }
            //obtener los usuarios que aplican a la oferta
            $usuarioAplicante = []; 
            foreach($aplicantesOferta as $apOferta){
                $usuarioAplicante[] = $this->usuarios->buscarRegistrosRelacionados('usuarios','usuarioID','usuarioAplicoID',$apOferta['usuarioAplicoID']);
            }
            //enviar la data
            $result->success = true;
            $result->result = [
                'ofertasPublicadas' = $ofertasPublicadas,
                'aplicantesOferta' = $aplicantesOferta,
                'usuariosAplicantes' = $usuarioAplicante,
            ];

            json_encode($result);

        }

        public function crearReserva(){

        }

        public function aplicar(){

        }


    }
?>