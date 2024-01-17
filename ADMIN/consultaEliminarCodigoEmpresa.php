<?php
session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../index.html');
    die();
}

include("../conexion.php");

$id_codigo = $_POST['codigo'];

$respuesta = array('mensaje' => 'estamos dentro');

$queryEliminar = mysqli_query($conn, "DELETE FROM codigo_empresa WHERE id_codigo_empresa = '$id_codigo' ");

if ($queryEliminar > 0) {
    $respuesta['mensaje'] = 'ok';
}
echo json_encode($respuesta);
