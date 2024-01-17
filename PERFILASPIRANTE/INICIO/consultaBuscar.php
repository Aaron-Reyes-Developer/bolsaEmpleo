<?php

session_start();

//si se intenta ingresar sin iniciar sesion
if ($_SESSION['id_aspirantes'] == null) {
    header('Location: ../../LOGIN/login.php');
    die();
}


include("../../conexion.php");

$query = $_POST['query'];
$id_carrera = $_POST['id_carrera'];

$pagina = $_POST['pagina'];
$limite = 15;
$desde = ($pagina - 1) * $limite;

$queryConsulta = " SELECT 
oft.id_oferta_trabajo,
oft.puesto,
oft.precio,
oft.ubicacion_empleo,
oft.tareas_realizar,
oft.detalle,
oft.fecha_oferta,
oft.estado_oferta,
hor.id_tipo_horario_oferta,
hor.nombre as hora,
tip_ofert.id_tipo_oferta,
tip_ofert.nombre as tipo_oferta,
tip_lu_oft.id_tipo_lugar_oferta,
tip_lu_oft.nombre as tipo_lugar,
car.id_carrera,
car.nombre_carrera as nombre_carrera,
usuEm.id_usuario_empresa,
dt.nombre as nombre_empresa

FROM oferta_trabajo  oft
INNER JOIN tipo_horario_oferta hor
ON hor.id_tipo_horario_oferta = oft.fk_id_horario

INNER JOIN tipos_oferta tip_ofert
ON tip_ofert.id_tipo_oferta = oft.fk_id_tipo_oferta

INNER JOIN tipo_lugar_oferta tip_lu_oft
ON tip_lu_oft.id_tipo_lugar_oferta = oft.fk_id_tipo_lugar_oferta

INNER JOIN carreras car
ON car.id_carrera = oft.fk_id_carrera 

INNER JOIN usuario_empresa as usuEm
ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa

INNER JOIN datos_empresa as dt
ON usuEm.id_usuario_empresa =  dt.fk_id_usuario_empresa


WHERE car.id_carrera = '$id_carrera' 
AND oft.estado_oferta = 1 ";


$queryConsulta .= $query;

$queryConsulta .= ' ORDER BY oft.fecha_oferta DESC LIMIT ' . $desde . ',' . $limite;

$consulta = mysqli_query($conn, $queryConsulta);

$datos = [];

while ($recorrerConsulta = mysqli_fetch_assoc($consulta)) {
    $datos[] = $recorrerConsulta;
}


echo json_encode($datos);
