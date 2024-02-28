<?php

session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../index.html');
    die();
}

if (!isset($_POST['nombreEmpresa'])) {
    die();
}

include("https://trabajounesum.com/conexion.php");


$respuesta = array('mensaje' => 'estamos dentro');


$nombreEmpresa = $_POST['nombreEmpresa'];
$rucEmpresa = $_POST['rucEmpresa'];
$codigo = $_POST['codigo'];


$queryInsertarCodigo = mysqli_query($conn, "INSERT INTO codigo_empresa (codigo_empresa, nombre_empresa, ruc) VALUES ('$codigo', '$nombreEmpresa', '$rucEmpresa') ");

if ($queryInsertarCodigo > 0) {
    $respuesta['mensaje'] = 'ok';
}

echo json_encode($respuesta);
