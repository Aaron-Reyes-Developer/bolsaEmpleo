<?php
session_start();

//si se ingresa por url sin antes pasar pro el por el primer registro
if ($_SESSION['seleccion-registro'] == null || $_SESSION['seleccion-registro'] == "") {
    header("Location: ../../../LOGIN/login.php");
    die();
}


$respuesta = array('mensaje' => 'estamos dentro');

// verificamos si la cedula no viene vacio
if ($_POST['cedula'] == "") {
    $respuesta['mensaje'] = 'Dato vacio';
    echo json_encode($respuesta);
    die();
}


include('../../../conexion.php');

// // DATOS POST
$cedula = htmlspecialchars($_POST['cedula']);

$queryBuscarCedula = mysqli_query($conn, "SELECT * FROM cedula WHERE cedula = '$cedula' AND fk_id_usuEstudiantes IS NULL ");
$recorerCedula = mysqli_fetch_array($queryBuscarCedula);


// verificar si existe la cedula en la base de datos
$numeroCount = mysqli_num_rows($queryBuscarCedula);

if ($numeroCount >= 1) {

    // DATOS CONSULTA
    $cedula = $recorerCedula['cedula'];
    $nombre = $recorerCedula['nombre'];
    $apellido = $recorerCedula['apellido'];

    // header("Location: ../REGISTRO1/registroAspirante1.php?cedula=$cedula&nombre=$nombre&apellido=$apellido");

    $respuesta['mensaje'] = 'ok';
    $respuesta['cedula'] = $cedula;
    $respuesta['nombre'] =  $nombre;
    $respuesta['apellido'] = $apellido;
} else {
    $respuesta['mensaje'] = 'Cedula Inexistente/Ocupada';
}

echo json_encode($respuesta);
