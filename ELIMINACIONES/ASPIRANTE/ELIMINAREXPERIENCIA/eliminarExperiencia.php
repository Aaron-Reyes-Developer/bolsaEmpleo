<?php
    session_start();

    if( !isset($_SESSION['okEliminar']) ){
        echo "no puedes entrar";
        die();
    }

    include('../../../conexion.php');

    $id_experiencia = $_REQUEST['id_experiencia'];

    $queryEliminar = "DELETE FROM experiencia WHERE id_experiencia = '$id_experiencia' ";
    $respuestaEliminar = mysqli_query($conn, $queryEliminar);

    if($respuestaEliminar){
        header('Location: ../../../PERFILASPIRANTE/perfilAspirante.php');
    }
?>