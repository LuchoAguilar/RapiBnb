<?php 
    class UserIntereses extends Orm{
        public function __construct($connect){
            parent :: __construct('usuarioIntID','usuario_interes',$connect);
        }
    }
?>