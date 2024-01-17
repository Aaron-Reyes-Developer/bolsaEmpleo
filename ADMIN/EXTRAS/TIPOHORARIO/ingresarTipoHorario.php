<?php

session_start();
if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../../index.html');
    die();
}


include('../../../conexion.php');

$respuesta = array('mensaje' => 'Estamos dentro', 'id' => 0);

if ($_POST['nombreHoraOferta'] == '') {
    $respuesta['mensaje'] = 'Datos Vacios';
}

$nombre = $_POST['nombreHoraOferta'];

$queryInsertar = mysqli_query($conn, "INSERT INTO tipo_horario_oferta (nombre) VALUES ('$nombre')");

// si la insersion sale bien
if ($queryInsertar > 0) {

    // consultamos el id del dato ingresado en la base de datos
    $queryConsultaHoraOferta = mysqli_query($conn, "SELECT * FROM tipo_horario_oferta WHERE nombre = '$nombre' ");
    $recorrerHoraOferta = mysqli_fetch_array($queryConsultaHoraOferta);

    // si sale bien la consulta
    if ($queryConsultaHoraOferta) {
        $respuesta['mensaje'] = 'DATOS INSERTADOS';
        $respuesta['id'] = $recorrerHoraOferta['id_tipo_horario_oferta'];
    }
} else {
    $respuesta['mensaje'] = 'ERROR AL INSERTAR DATOS';
}


echo json_encode($respuesta);
