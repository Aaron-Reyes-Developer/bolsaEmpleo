<?php

session_start();

if (!isset($_SESSION['id_empresa'])) {
    header("Location: ../../LOGIN/login.php");
    die();
}


include("../../conexion.php");

// 

$pagina =  $_POST['pagina'] ?? 1;
$limite = 10;
$desde = ($pagina - 1) * $limite;

$queryBuscar = "SELECT 
usu.id_usuEstudiantes, 
concat(dat.nombre,' ',dat.apellido) nombres, 
dat.imagen_perfil,
cv.detalle_curriculum,
car.nombre_carrera,
cv.especializacion_curriculum,
cv.estado_trabajo
FROM usuario_estudiantes as usu 
LEFT JOIN datos_estudiantes as dat 
ON usu.id_usuEstudiantes = dat.fk_id_usuEstudiantes 
INNER JOIN carreras car 
ON car.id_carrera = dat.fk_id_carrera
LEFT JOIN curriculum as cv 
ON usu.id_usuEstudiantes = cv.fk_id_usuEstudiantes 
LEFT JOIN conocimientos as cono 
ON cv.id_curriculum = cono.fk_id_curriculum 
LEFT JOIN experiencia as xp 
ON cv.id_curriculum = xp.fk_id_curriculum 
LEFT JOIN educacion as edu 
ON cv.id_curriculum = edu.fk_id_curriculum 
WHERE usu.estado_cuenta = 1";





// BUSCAR POR TODOS LOS CAMPOS  
if (isset($_POST['apellido']) && isset($_POST['filtrar_carrera']) && isset($_POST['filtrar_estado']) && isset($_POST['especializacion'])) {

    // datos
    $apellido = $_POST['apellido'];
    $filtrar_carrera = $_POST['filtrar_carrera'];
    $filtrar_estado = $_POST['filtrar_estado'];
    $especializacion = $_POST['especializacion'];

    $queryBuscar .= " AND dat.apellido LIKE '%$apellido%'  AND  car.nombre_carrera = '$filtrar_carrera' AND cv.estado_trabajo = '$filtrar_estado'  AND cv.especializacion_curriculum = '$especializacion' ";
}


// BUSCAR POR NOMBRE , CARRERA Y ESTADO
if (isset($_POST['apellido']) && isset($_POST['filtrar_carrera']) && isset($_POST['filtrar_estado']) && !isset($_POST['especializacion'])) {

    // datos
    $apellido = $_POST['apellido'];
    $filtrar_carrera = $_POST['filtrar_carrera'];
    $filtrar_estado = $_POST['filtrar_estado'];

    $queryBuscar .= " AND dat.apellido LIKE '%$apellido%'  AND  car.nombre_carrera = '$filtrar_carrera' AND cv.estado_trabajo = '$filtrar_estado'";
}


// BUSCAR POR CARRERA , ESTADO , ESPECIALIZACION
if (!isset($_POST['apellido']) && isset($_POST['filtrar_carrera']) && isset($_POST['filtrar_estado']) && isset($_POST['especializacion'])) {

    // datos
    $filtrar_carrera = $_POST['filtrar_carrera'];
    $filtrar_estado = $_POST['filtrar_estado'];
    $especializacion = $_POST['especializacion'];

    $queryBuscar .= " AND  car.nombre_carrera = '$filtrar_carrera' AND  cv.estado_trabajo = '$filtrar_estado' AND  cv.especializacion_curriculum = '$especializacion'  ";
}



// BUSCAR POR NOMBRE Y CARRERA 
if (isset($_POST['apellido']) && isset($_POST['filtrar_carrera']) && !isset($_POST['filtrar_estado']) && !isset($_POST['especializacion'])) {

    // datos
    $apellido = $_POST['apellido'];
    $filtrar_carrera = $_POST['filtrar_carrera'];


    $queryBuscar .= " AND dat.apellido LIKE '%$apellido%'  AND  car.nombre_carrera = '$filtrar_carrera' ";
}


// BUSCAR POR NOMBRE
if (isset($_POST['apellido']) && !isset($_POST['filtrar_carrera']) && !isset($_POST['filtrar_estado']) && !isset($_POST['especializacion'])) {

    // datos
    $apellido = $_POST['apellido'];

    $queryBuscar .= " AND dat.apellido LIKE '%$apellido%' ";
}


// BUSCAR POR CARRERA
if (!isset($_POST['apellido']) && isset($_POST['filtrar_carrera']) && !isset($_POST['filtrar_estado']) && !isset($_POST['especializacion'])) {

    // datos
    $filtrar_carrera = $_POST['filtrar_carrera'];

    $queryBuscar .= " AND  car.nombre_carrera = '$filtrar_carrera' ";
}


// BUSCAR POR ESTADO DE TRABAJO
if (!isset($_POST['apellido']) && !isset($_POST['filtrar_carrera']) && isset($_POST['filtrar_estado']) && !isset($_POST['especializacion'])) {

    // datos
    $filtrar_estado = $_POST['filtrar_estado'];

    $queryBuscar .= " AND  cv.estado_trabajo = '$filtrar_estado' ";
}


// BUSCAR POR ESPECIALIZACION
if (!isset($_POST['apellido']) && !isset($_POST['filtrar_carrera']) && !isset($_POST['filtrar_estado']) && isset($_POST['especializacion'])) {

    // datos
    $especializacion = $_POST['especializacion'];

    $queryBuscar .= " AND  cv.especializacion_curriculum = '$especializacion' ";
}


// BUSCAR CARRERA Y ESTADO
if (!isset($_POST['apellido']) && isset($_POST['filtrar_carrera']) && isset($_POST['filtrar_estado']) && !isset($_POST['especializacion'])) {

    // datos
    $filtrar_carrera = $_POST['filtrar_carrera'];
    $filtrar_estado = $_POST['filtrar_estado'];

    $queryBuscar .= " AND  car.nombre_carrera = '$filtrar_carrera' AND  cv.estado_trabajo = '$filtrar_estado' ";
}


// BUSCAR  POR ESTADO Y ESPECIALIZACION
if (!isset($_POST['apellido']) && !isset($_POST['filtrar_carrera']) && isset($_POST['filtrar_estado']) && isset($_POST['especializacion'])) {

    // datos
    $filtrar_estado = $_POST['filtrar_estado'];
    $especializacion = $_POST['especializacion'];

    $queryBuscar .= " AND  cv.estado_trabajo = '$filtrar_estado'  AND cv.especializacion_curriculum = '$especializacion' ";
}


// total de resultados
$queryTotalResultado = $queryBuscar . "group by usu.id_usuEstudiantes";
$totalResultados = mysqli_query($conn, $queryTotalResultado);
$totalDatos = mysqli_num_rows($totalResultados);


// AGRUPAMOS LA CONSULTA
$queryBuscar .= " group by usu.id_usuEstudiantes ORDER BY usu.id_usuEstudiantes DESC LIMIT $desde, $limite";



$data = [];


// ACEMOS LA CONSULTA
$consulta = mysqli_query($conn, $queryBuscar);




// AGREGAMAOS LOS DATOS EN UN ARRAY 
while ($recorrerConsulta = mysqli_fetch_array($consulta)) {

    $usuario = [
        'id_usuEstudiantes' => $recorrerConsulta['id_usuEstudiantes'],
        'nombres' => $recorrerConsulta['nombres'],
        'imagen_perfil' => base64_encode($recorrerConsulta['imagen_perfil']), // Convierte la imagen a base64
        'nombre_carrera' => $recorrerConsulta['nombre_carrera'],
        'detalle_curriculum' => $recorrerConsulta['detalle_curriculum'],
        'especializacion_curriculum' => $recorrerConsulta['especializacion_curriculum'],
        'estado_trabajo' => $recorrerConsulta['estado_trabajo']
    ];


    $data[] = $usuario;
}


// echo json_encode(
//     [
//         'data' => $data,
//         'quedan' => ($desde + $limite) < $totalDatos,
//         'totalAspirantes' => $totalDatos
//     ]
// );


echo json_encode($data);
