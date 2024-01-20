<?php

if (!isset($_POST['id_aspirante'])) {
    echo "no puedes estar aqui";
    die();
}

include("../../conexion.php");


// DATOS
$id_aspirante = $_POST['id_aspirante'];
$id_oferta = $_POST['id_oferta'];


$queryGuardar = mysqli_query($conn, "INSERT INTO estudiantes_guardados (fk_id_usuario_estudiante, fk_id_oferta_trabajo) VALUES ( '$id_aspirante', '$id_oferta')");

if ($queryGuardar) {
    echo json_encode(array('mensaje' => 'ok'));
} else {
    echo json_encode(array('mensaje' => 'nop'));
}
