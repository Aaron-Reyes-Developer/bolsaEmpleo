<?php

session_start();

if (!isset($_SESSION['id_empresa'])) {
    header("Location: ../../LOGIN/login.php");
    die();
}

if (
    ($_POST['nombrePuesto'] == "") ||
    ($_POST['tipo_empleo'] == "") ||
    ($_POST['tipo_lugar'] == "") ||
    ($_POST['tipo_carrera'] == "") ||
    ($_POST['detalle_empleo'] == "") ||
    ($_POST['horario'] == "") ||
    ($_POST['ubicacion_empleo'] == "")


) {
    echo json_encode(array('mensaje' => 'Datos vacios'));
    die();
}


include("../../conexion.php");

// DATOS 
$id_oferta = $_POST['id_oferta'];
$nombrePuesto = htmlspecialchars($_POST['nombrePuesto']);
$precio = htmlspecialchars($_POST['precio']);
$ubicacion_empleo = htmlspecialchars($_POST['ubicacion_empleo']);
$tareas_realizar = htmlspecialchars($_POST['tareas_realizar']);
$detalle_empleo = htmlspecialchars($_POST['detalle_empleo']);
$horario = htmlspecialchars($_POST['horario']);
$tipo_empleo = htmlspecialchars($_POST['tipo_empleo']);
$tipo_lugar_oferta = htmlspecialchars($_POST['tipo_lugar']);
$tipo_carrera = htmlspecialchars($_POST['tipo_carrera']);
$id_empresa = $_SESSION['id_empresa'];


// guardar datos

if ($id_oferta == 0) {

    // INGRESAR LOS DATOS A LA BASE DE DATOS
    $queryIngresar = "INSERT INTO oferta_trabajo 
    (puesto, precio, ubicacion_empleo, tareas_realizar, 
    detalle, fk_id_horario  ,fk_id_tipo_oferta , fk_id_tipo_lugar_oferta , 
    fk_id_carrera , fk_id_usuario_empresa ) 
    VALUES 
    ('$nombrePuesto', '$precio', 
    '$ubicacion_empleo', '$tareas_realizar', 
    '$detalle_empleo', '$horario' ,
    '$tipo_empleo', '$tipo_lugar_oferta',  '$tipo_carrera'
    ,  '$id_empresa' )";

    // 
} else { //actualizar datos

    $queryIngresar = " UPDATE oferta_trabajo 
    SET 
    puesto = '$nombrePuesto' ,
    precio = '$precio',
    ubicacion_empleo = '$ubicacion_empleo',
    tareas_realizar = '$tareas_realizar',
    detalle = '$detalle_empleo',
    fk_id_horario = '$horario',
    fk_id_tipo_oferta = '$tipo_empleo',
    fk_id_tipo_lugar_oferta = '$tipo_lugar_oferta',
    fk_id_carrera = '$tipo_carrera'
    WHERE id_oferta_trabajo = $id_oferta ";
}






$respuesta = mysqli_query($conn, $queryIngresar);

if ($respuesta) {




    ///////////////////////////////////     LOGICA PARA AGREGAR LOS REQUISITOS ///////////////////////////

    $totalRequisitos = $_POST['totalRequisito'];

    // guardar datos
    if ($id_oferta == 0) {

        // sacamos el id del registro
        $id_oferta = mysqli_insert_id($conn);

        for ($i = 0; $i < $totalRequisitos; $i++) {

            $keyRequisito = "requisito" . $i + 1;

            $detalle = $_POST[$keyRequisito];

            // queiry para guardar datos
            $queryIngresarRequisito = mysqli_query($conn, "INSERT INTO requisitos (detalle, fk_id_oferta_trabajo) VALUES ('$detalle', $id_oferta) ");
        }

        //
    } else { // editar datos


        for ($i = 0; $i < $totalRequisitos; $i++) {

            $keyRequisito = "requisito" . $i + 1;

            $detalle = $_POST[$keyRequisito];

            $queryIngresarRequisito = mysqli_query($conn, "UPDATE requisitos 
            SET detalle = '$detalle' WHERE fk_id_oferta_trabajo = $id_oferta ");
        }
    }


    echo json_encode(array('mensaje' => 'ok'));
} else {
    echo json_encode(array('mensaje' => 'nop'));
}
