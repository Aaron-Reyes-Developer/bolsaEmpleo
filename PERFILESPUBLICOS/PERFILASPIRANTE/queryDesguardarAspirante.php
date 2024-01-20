<?php

if (!isset($_POST['id_aspirante'])) {
    echo "no puedes estar aqui";
    die();
}

include("../../conexion.php");


// DATOS
$id_aspirante = $_POST['id_aspirante'];
$id_oferta = $_POST['id_oferta'];


$queryEliminar = mysqli_query($conn, "DELETE FROM estudiantes_guardados WHERE fk_id_usuario_estudiante  = $id_aspirante AND fk_id_oferta_trabajo = $id_oferta");

if ($queryEliminar) {
    echo json_encode(array('mensaje' => 'ok'));
} else {
    echo json_encode(array('mensaje' => 'nop'));
}
