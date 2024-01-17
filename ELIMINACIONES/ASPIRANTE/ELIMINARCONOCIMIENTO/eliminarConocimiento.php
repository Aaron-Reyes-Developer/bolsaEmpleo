<?php
    session_start();

    if( !isset($_SESSION['okEliminar']) ){
        echo "no puedes entrar";
        die();
    }

    include('../../../conexion.php');

    $id_conocimientos = $_REQUEST['id_conocimientos'];

    $queryEliminar = "DELETE FROM conocimientos WHERE id_conocimientos = '$id_conocimientos' ";
    $respuestaEliminar = mysqli_query($conn, $queryEliminar);

    if($respuestaEliminar){
        header('Location: ../../../PERFILASPIRANTE/perfilAspirante.php');
    }
?>