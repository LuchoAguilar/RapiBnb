<?php
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/reserva.php');
    require_once(__DIR__.'/../Model/ofertaAlquiler.php');

    class PageController extends Controller{

        private $userSessionControl;

        public function __construct($connect,$session){
            $this->userSessionControl = new ControladorDeSessiones($session,$connect);
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
        */
        public function listarOfertas(){
            //tienen que ser solo con estado:publicado y de users normales.
        }

        public function listarOfertasVerificados(){
            // son las ofertas que deben aparecer de manera destacada
        }

        public function listarRecomentaciones(){
            // son las que salen del pareo de intereses(usuario) y etiquetas(ofertas alquiler)
        }

        //---------------------------------------------------------------------------------------------------------------------------//
    }
?>