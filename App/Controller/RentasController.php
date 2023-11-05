<?php 
    require_once(__DIR__.'/../Model/controladorDeSessiones.php');
    require_once(__DIR__.'/../Model/ofertaAlquiler.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/reserva.php');
    require_once(__DIR__.'/../Model/aplicaOferta.php');

    class RentasController extends Controller{

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
                $this->render('rentas', [] ,'site');
            }else{
                header("Location: ".URL_PATH);
            }
        }

        public function table() {

            $result = new Result();
            $userID = $this->userSession->ID();
            $esVerificado = $this->userSession->esVerificado();
            // Obtener solo las que están publicadas
            $ofertasPublicadasUser = $this->obtenerOfertasPublicadas($userID); 

            // Obtener aplicantes de oferta
            $aplicantesAlasOfertasUser = '';
            $oferta_con_aplicante = '';
            if($ofertasPublicadasUser != null){
                
                foreach ($ofertasPublicadasUser as $oferPublicada) {
                    $aplicantesAlasOfertasUser = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $oferPublicada['ofertaID']);
                }
                // obtener las ofertas publicadas del usuario con sus respectivos aplicantes(puede ser solo ofertas publicadas o el arreglo vacio)
                
                if(count($ofertasPublicadasUser) > 0 || count($aplicantesAlasOfertasUser) > 0){
                    $usuarios = $this->usuarios->getAll();
                    $oferta_con_aplicante = $this->ofertas_y_Aplicantes($usuarios, $ofertasPublicadasUser, $aplicantesAlasOfertasUser);
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

            // pasar las reservas que hizo el usuario
            $reservasUsuario = $this->reserva->buscarRegistrosRelacionados('usuarios','usuarioID','autorID',$userID);
            $result->message = $reservasUsuario;
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
                        'reservas' => $this->reserva->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $ofertaPublicada['ofertaID']),
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
                'esVerificado' => $esVerificado,
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
                foreach($aplicantesAlasOfertasUser as $renta){
                    if($renta['estado'] === ESPERA_RENTA){
                        foreach($usuariosAll as $usuario){
                            if($renta['usuarioAplicoID'] === $usuario['usuarioID']){
                                foreach($ofertasPublicadasUser as $ofertaUser){
                                    if($renta['ofertaAlquilerID'] === $ofertaUser['ofertaID']){
                                        $oferta_con_aplicante[] = [
                                            'ofertaPublicada' => $ofertaUser,
                                            'usuarioAplicante' => $usuario,
                                        ];
                                    }
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
        //---------------------------------------------------- Reservas reseñar, puntuar y contestar reseña-------------------------------------------------------------//

        public function resenar(){
            $result = new Result();
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idReserva = (isset($_POST['reservaID']))? $_POST['reservaID']:'';
                $esVerificado = (isset($_POST['esVerificado']))? $_POST['esVerificado']:''; 
                $resena = (isset($_POST['nuevaResena']))? $_POST['nuevaResena']:'';
                $puntuacion = (isset($_POST['nuevaPuntuacion']))? $_POST['nuevaPuntuacion']:'';
                if($idReserva != null && $esVerificado != null && is_numeric($idReserva)){

                    if(empty($resena) && $puntuacion != null){
                        $this->reserva->updateById($idReserva,[
                            'puntaje' => $puntuacion,
                        ]);
                        $result->success = true;
                        $result->message = "Evaluación realizada con éxito.";
                    }else{
                        if($esVerificado === 'true' && $resena != null && $puntuacion != null){
                            $this->reserva->updateById($idReserva,[
                                'textoReserva' => $resena,
                                'puntaje' => $puntuacion,
                            ]);
    
                            $result->success = true;
                            $result->message = "Evaluación realizada con éxito.";
                        }else{
                            $result->success = false;
                            $result->message = "Error: usuario no verificado.".$puntuacion.$resena;
                        }
                    }
                    
                    
                }else{
                    $result->success = false;
                    $result->message = "Error: información inválida.";
                }

            }else{
                $result->success = false;
                $result->message = 'Error: Solicitud inválida.';
            }
            echo json_encode($result);
        }

        public function responder(){
            $result = new Result();
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idReserva = (isset($_POST['reservaID']))? $_POST['reservaID']:'';
                $contestacion = (isset($_POST['responder']))? $_POST['responder']:'';

                if($idReserva != null && is_numeric($idReserva) && $contestacion != null){
                    $this->reserva->updateById($idReserva,[
                        'respuesta' => $contestacion,
                    ]);
                    $result->success = true;
                    $result->message = "Respuesta enviada con éxito.";
                }else{
                    $result->success = false;
                    $result->message = "Error: información inválida.";
                }
            }else{
                $result->success = false;
                $result->message = 'Error: Solicitud invalida.';
            }
            echo json_encode($result);
        }

        //---------------------------------------------------------------------------------------------------------------------------------------------//

        //---------------------------------------------------------Rentas--------------------------------------------------------------------//

        public function crearReserva($idUsuarioReserva,$idOferta){
            $result = [];
            if(is_numeric($idUsuario) && is_numeric($idOferta)){
                $rentas = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $idOferta);
                if($rentas){
                    foreach($rentas as $renta){
                        if($renta['usuarioAplicoID'] === $idUsuario){
                            $this->aplicaOferta->updateById($renta['aplicacionID'],[
                                'estado' => ACEPTADO,
                            ]);
                        }
                    } 
                    $this->reserva->insert([
                        'ofertaAlquilerID' => $idOferta,
                        'autorID' => $idUsuarioReserva,
                    ]);
                    $result = [
                        'success' => true,
                        'message' => 'Reserva creada con éxito.',
                    ];
                }else{
                    $result = [
                        'success' => false,
                        'message' => 'Error: no existe la aplicación a la oferta hecha por el usuario.',
                    ];
                }
            }else{
                $result = [
                    'success' => false,
                    'message' => 'Error: información inválida.',
                ];
            }
            return $result;   
        }

        public function rentar(){
            //upsert para usuarios no verificados e insert comun para los verificados.
            $result = new Result();
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idOferta = (isset($_POST['ofertaID']))? $_POST['ofertaID']:'';
                $idUsuario = $this->userSession->ID();
                $esVerificado = $this->userSession->esVerificado();
                if($idOferta != '' && $idUsuario !='' && is_numeric($idUsuario) && is_numeric($idOferta) && $esVerificado != null){
                    //si es verificado debe crear la aplicacion con estado aceptado else con estado espera
                    if($esVerificado === 'true'){
                        $this->aplicaOferta->insert([
                            'estado' => ACEPTADO,
                            'usuarioAplicoID' => $idUsuario,
                            'ofertaAlquilerID' => $idOferta,
                        ]);
                        $resultado = crearReserva($idUsuario,$idOferta);
                        if($resultado['success'] === true){
                            $result->success = true;
                            $result->message = "Renta verificada creada con éxito.";
                        }else{
                            $result->success = $resultado['success'];
                            $result->message = $resultado['message'];
                        }
                        
                    }else{
                        $rentasUser = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $idUsuario);
                        if(count($rentasUser) > 0){
                            $rentaId = '';
                            foreach($rentasUser as $renta){
                                $rentaId = $renta['aplicacionID'];
                            }
                            $renta = $this->aplicaOferta->getById($rentaId);
                            if($renta){
                                if($renta['estado'] === ESPERA_RENTA){
                                    $this->aplicaOferta->insert([
                                        'estado' => ESPERA_RENTA,
                                        'usuarioAplicoID' => $idUsuario,
                                        'ofertaAlquilerID' => $idOferta,
                                    ]);
                                    $result->success = true;
                                    $result->message = "Renta creada con éxito.";
                                }else{
                                    $result->success = false;
                                    $result->message = "Error: usuario tiene ya tiene una renta en proceso.";
                                }
                            }else{
                                $result->success = false;
                                $result->message = "Error: de proceso.";
                            }
                        }else{
                            $this->aplicaOferta->insert([
                                'estado' => ESPERA_RENTA,
                                'usuarioAplicoID' => $idUsuario,
                                'ofertaAlquilerID' => $idOferta,
                            ]);
                            $result->success = true;
                            $result->message = "Renta creada con éxito.";
                        }
                        
                    }

                }else{
                    $result->success = false;
                    $result->message = "Error: información inválida.";
                } 
            }else{
                $result->success = false;
                $result->message = 'Error: Solicitud invalida.';
            }
            echo json_encode($result);
        }

        public function aceptarRenta(){
            $result = new Result();
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idOferta = (isset($_POST['ofertaID']))? $_POST['ofertaID']:'';
                $idUsuario = (isset($_POST['usuarioID'])) ? $_POST['usuarioID']:'';
                if($idOferta != '' && $idUsuario !='' && is_numeric($idUsuario) && is_numeric($idOferta)){
                    $resultado = crearReserva($idUsuario,$idOferta);
                    if($resultado['success'] === true){
                        $result->success = true;
                        $result->message = "Reserva creada con éxito.";
                    }else{
                        $result->success = $resultado['success'];
                        $result->message = $resultado['message'];
                    }
                }else{
                    $result->success = false;
                    $result->message = "Error: información inválida.";
                }
            }else{
                $result->success = false;
                $result->message = 'Error: Solicitud invalida.';
            }
            echo json_encode($result);
        }

        public function rechazarRenta(){
            $result = new Result();
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $idOferta = (isset($_POST['ofertaID']))? $_POST['ofertaID']:'';
                $idUsuario = (isset($_POST['usuarioID'])) ? $_POST['usuarioID']:'';
                if($idOferta != '' && $idUsuario !='' && is_numeric($idUsuario) && is_numeric($idOferta)){
                    $rentas = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $idOferta);
                    if($rentas){
                        foreach($rentas as $renta){
                            if($renta['usuarioAplicoID'] === $idUsuario){
                                $this->aplicaOferta->updateById($renta['aplicacionID'],[
                                    'estado' => RECHAZADO,
                                ]);
                            }
                        }
                        $result->success = true;
                        $result->message = "renta rechazada con éxito.";
                    }else{
                        $result->success = false;
                        $result->message = 'Error: Oferta no encontrada.';
                    } 
                }else{
                    $result->success = false;
                    $result->message = 'Error: información invalida.';
                }

            }else{
                $result->success = false;
                $result->message = 'Error: Solicitud invalida.';
            }
            echo json_encode($result);
        }
        //---------------------------------------------------------------------------------------------------------------------------------------------//

    }
    //---------------------------------------------------------------------------------------------------------------------------------------------//
?>