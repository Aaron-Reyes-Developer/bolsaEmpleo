<?php

session_start();

//si se intenta ingresar sin iniciar sesion
if ($_SESSION['id_aspirantes'] == null) {
    header('Location: ../../LOGIN/login.php');
    die();
}


include("../../conexion.php");

$id_aspirante = $_POST['id_aspirante'];
$id_oferta = $_POST['id_oferta'];

$respuesta = array('mensaje' => 'estamos dentro');

$queryBuscar = mysqli_query($conn, "SELECT * FROM postula WHERE fk_id_oferta_trabajo = '$id_oferta' and fk_id_usuEstudiantes = '$id_aspirante' ");

// significa que ya se postulo en la oferta
if (mysqli_num_rows($queryBuscar) > 0) {
    $respuesta['mensaje'] = 'Postulado';
}

echo json_encode($respuesta);
