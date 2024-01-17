<?php

session_start();

if (!isset($_SESSION['id_empresa'])) {
    header("Location: ../../LOGIN/login.php");
    die();
}

include("../../conexion.php");

$id_oferta = $_POST['id'];

$queryEliminar = mysqli_query($conn, "UPDATE oferta_trabajo SET estado_oferta = '0' WHERE id_oferta_trabajo = $id_oferta");

if ($queryEliminar) {
    echo json_encode(array('mensaje' => 'ok'));
} else {
    echo json_encode(array('mensaje' => 'nop'));
}
