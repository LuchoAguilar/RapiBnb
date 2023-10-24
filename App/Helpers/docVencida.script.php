<?php
    require_once(__DIR__.'/../Services/conexion.php');
    require_once(__DIR__.'/../Model/usuario.php');
    require_once(__DIR__.'/../Model/documentacion.php');

    $conexion = new Conexion();
    $connect = $conexion->getConexion();

    $usuario = new Usuario($connect);
    $documentacion = new Documentacion($connect);

    // Obtener todas las documentaciones y verificar si han caducado
    $documentaciones = $documentacion->getAll();
    $date = new DateTime();

    foreach ($documentaciones as $doc) {
        $fechaVencimiento = new DateTime($doc['fechaVencimiento']);

        if ($fechaVencimiento < $date) {
            // Eliminar la documentación
            $documentacionPath = 'Assets/images/documentacion/' . $doc['documentoAdjunto'];

            if (file_exists($documentacionPath)) {
                unlink($documentacionPath);
            }

            $documentacion->deleteById($doc['certificacionID']);

            // Actualizar registros relacionados en la base de datos
            $userDoc = $usuario->buscarRegistrosRelacionados('certificacion', 'certificacionID', 'documentacionID', $doc['usarioAVerfID']);

            foreach ($userDoc as $user) {
                $usuario->updateById($user['usuarioID'], [
                    'documentacionID' => null,
                ]);
            }
        }
    }

?>