<?php

session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../../index.html');
    die();
}


include('../../../conexion.php');

$respuesta = array('mensaje' => 'Estamos dentro');

if ($_POST['id'] == '') {
    $respuesta['mensaje'] = 'Datos Vacios';
}

$id = $_POST['id'];


$queryEliminar = mysqli_query($conn, "DELETE FROM publicidad WHERE id_publicidad = $id ");

// si la insersion sale bien
if ($queryEliminar > 0) {

    $respuesta['mensaje'] = 'ok';
} else {
    $respuesta['mensaje'] = 'ERROR AL ELIMINAR DATOS';
}


echo json_encode($respuesta);
