<?php

session_start();
if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../../index.html');
    die();
}


include('../../../conexion.php');

$respuesta = array('mensaje' => 'Estamos dentro', 'id' => 0);

if ($_POST['nombreLugarOferta'] == '') {
    $respuesta['mensaje'] = 'Datos Vacios';
}

$nombre = $_POST['nombreLugarOferta'];

$queryInsertar = mysqli_query($conn, "INSERT INTO tipo_lugar_oferta (nombre) VALUES ('$nombre')");

// si la insersion sale bien
if ($queryInsertar > 0) {

    // consultamos el id del dato ingresado en la base de datos
    $queryConsultaLugarOferta = mysqli_query($conn, "SELECT * FROM tipo_lugar_oferta WHERE nombre = '$nombre' ");
    $recorrerLugarOferta = mysqli_fetch_array($queryConsultaLugarOferta);

    // si sale bien la consulta
    if ($queryConsultaLugarOferta) {
        $respuesta['mensaje'] = 'DATOS INSERTADOS';
        $respuesta['id'] = $recorrerLugarOferta['id_tipo_lugar_oferta'];
    }
} else {
    $respuesta['mensaje'] = 'ERROR AL INSERTAR DATOS';
}


echo json_encode($respuesta);
