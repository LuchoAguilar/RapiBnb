<?php
    class PageController extends Controller{

        public function __construct($connect,$session){

        }

        public function home(){
           $this->render('home',[],'noLog');
        }
        public function listar(){
            echo "listar";
        }
        public function logOut(){
            echo "cerrar session";
        }
        public function eliminar(){
            echo "eliminar";
        }
    }
?>