<?php
session_start();
if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../../index.html');
    die();
}


include('../../../conexion.php');

$respuesta = array('mensaje' => 'Estamos dentro', 'id' => 0);

if ($_POST['nombreOferta'] == '') {
    $respuesta['mensaje'] = 'Datos Vacios';
}

$nombre = $_POST['nombreOferta'];

$queryInsertar = mysqli_query($conn, "INSERT INTO tipos_oferta (nombre) VALUES ('$nombre')");

// si la insersion sale bien
if ($queryInsertar > 0) {

    // consultamos el id del dato ingresado en la base de datos
    $queryConsultaTipoOferta = mysqli_query($conn, "SELECT * FROM tipos_oferta WHERE nombre = '$nombre' ");
    $recorrerTipoOferta = mysqli_fetch_array($queryConsultaTipoOferta);

    // si sale bien la consulta
    if ($queryConsultaTipoOferta) {
        $respuesta['mensaje'] = 'DATOS INSERTADOS';
        $respuesta['id'] = $recorrerTipoOferta['id_tipo_oferta'];
    }
} else {
    $respuesta['mensaje'] = 'ERROR AL INSERTAR DATOS';
}


echo json_encode($respuesta);
