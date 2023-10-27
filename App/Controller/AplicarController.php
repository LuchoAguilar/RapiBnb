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
            //hay que pasar sus propias aplicaciones.

            $oferta_con_aplicante = [];
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
        
            // Enviar la data
            $result->success = true;
            $result->result = [
                'ofertasAplicantes' => $oferta_con_aplicante,
            ];
        
            echo json_encode($result);
        }
        

        public function crearReserva(){

        }

        public function aplicar(){

        }


    }
?>