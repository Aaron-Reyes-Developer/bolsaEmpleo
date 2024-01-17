<?php
    session_start();

    if( !isset($_SESSION['okEliminar']) ){
        echo "no puedes entrar";
        die();
    }

    include('../../../conexion.php');

    $id_Educacion = $_REQUEST['id_educacion'];

    $queryEliminar = "DELETE FROM educacion WHERE id_educacion = '$id_Educacion' ";
    $respuestaEliminar = mysqli_query($conn, $queryEliminar);

    if($respuestaEliminar){
        header('Location: ../../../PERFILASPIRANTE/perfilAspirante.php');
    }
?>