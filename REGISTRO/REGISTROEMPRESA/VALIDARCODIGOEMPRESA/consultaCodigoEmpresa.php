<?php

if (!isset($_POST['codigo'])) {
    header('Location: ../../registro.php');
    die();
}

include('../../../conexion.php');

$codigo = htmlspecialchars($_POST['codigo']);

$respuesta = array('mensaje' => 'estamos dentro');


$queryConsulta = mysqli_query($conn, "SELECT id_codigo_empresa FROM codigo_empresa WHERE codigo_empresa = '$codigo' ");

if (mysqli_num_rows($queryConsulta) > 0) {
    $respuesta['mensaje'] = 'ok';
} else {
    $respuesta['mensaje'] = 'nop';
}
echo json_encode($respuesta);
