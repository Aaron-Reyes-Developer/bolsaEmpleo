<?php
    session_start();

    if( !isset($_SESSION['okEliminar']) ){
        echo "no puedes entrar";
        die();
    }

    include('../../../conexion.php');

    $id_idioma = $_REQUEST['id_idioma'];

    $queryEliminar = "DELETE FROM idioma WHERE id_idioma = '$id_idioma' ";
    $respuestaEliminar = mysqli_query($conn, $queryEliminar);

    if($respuestaEliminar){
        header('Location: ../../../PERFILASPIRANTE/perfilAspirante.php');
    }
?>