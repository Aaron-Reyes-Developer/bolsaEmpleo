<?php
session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../index.html');
    die();
}


$respuesta = array('mensaje' => 'Estamos dentro', 'id' => '', 'cedula' => '', 'nombre' => '', 'apellido' => '');


// si los datos estan vacios
if ($_POST['cedula'] == '' || $_POST['nombre'] == '' || $_POST['apellido'] == '') {

    $respuesta['mensaje'] = "datos vacios";
    echo json_encode($respuesta);
    die();
}

include('../../conexion.php');

// DATOS
$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];


// verificar si la cedula existe
$queryExisteCedula = mysqli_query($conn, "SELECT * FROM cedula WHERE cedula = '$cedula' ");
$numRegistrosCedula = mysqli_num_rows($queryExisteCedula);
while (mysqli_next_result($conn)) {;
}
if ($numRegistrosCedula > 0) {

    $respuesta['mensaje'] = 'La Cedula ya Existe.';
    echo json_encode($respuesta);
    die();
}



$queryInsertarCedula = mysqli_query($conn, "INSERT INTO cedula (cedula, nombre, apellido) VALUES ('$cedula','$nombre','$apellido') ");
while (mysqli_next_result($conn)) {;
}


// consulta para enviar los datos a la tabla
$queryCedulas = mysqli_query($conn, "SELECT cedu.id_cedula, cedu.cedula, cedu.nombre as nombreCe, cedu.apellido as apellidoCe, datos.id_datos_estudiantes FROM usuario_estudiantes as usuEs   
                                            RIGHT JOIN cedula as cedu   ON usuEs.id_usuEstudiantes = cedu.fk_id_usuEstudiantes 
                                            LEFT JOIN datos_estudiantes as datos ON usuEs.id_usuEstudiantes = datos.fk_id_usuEstudiantes
                                            WHERE cedu.cedula = '$cedula' AND cedu.nombre ='$nombre' AND cedu.apellido ='$apellido' ");

$recorrerCedulas = mysqli_fetch_array($queryCedulas);


if ($queryCedulas) {

    $respuesta['mensaje'] = 'ok';
    $respuesta['id'] = $recorrerCedulas['id_cedula'];
    $respuesta['cedula'] = $recorrerCedulas['cedula'];
    $respuesta['nombre'] = $recorrerCedulas['nombreCe'];
    $respuesta['apellido'] = $recorrerCedulas['apellidoCe'];
} else {
    $respuesta['mensaje'] = 'error consulta';
}


echo json_encode($respuesta);
