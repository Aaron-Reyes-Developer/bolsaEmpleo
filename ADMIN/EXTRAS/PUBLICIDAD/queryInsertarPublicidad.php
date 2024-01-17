<?php

session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../../index.html');
    die();
}


include('../../../conexion.php');

$respuesta = array('mensaje' => 'Estamos dentro');

if ($_POST['detalle'] == '') {
    $respuesta['mensaje'] = 'Datos Vacios';
}

$detalle = $_POST['detalle'];
$link = $_POST['link'];
$fecha_caducidad = $_POST['fechaCaducidad'];
$carrera_dirigida = $_POST['carreraDirigida'];



$queryInsertar = mysqli_query($conn, "INSERT INTO publicidad (detalle, link, fecha_caducidad, fk_id_carrera) VALUES ('$detalle', '$link', '$fecha_caducidad', '$carrera_dirigida')");

// si la insersion sale bien
if ($queryInsertar > 0) {

    $respuesta['mensaje'] = 'ok';
} else {
    $respuesta['mensaje'] = 'ERROR AL INSERTAR DATOS';
}


echo json_encode($respuesta);
