<?php

session_start();

if (empty($_SESSION['id_usuario'])) {
    echo json_encode(array('mensaje' => 'No puedes estar aqui'));
    die();
}


// entra si todo esta bien
include("../../../conexion.php");

$respuesta = array('mensaje' => 'estamos dentro');




// verificar si los datos no estan vacios
// if (
//     $_POST['nombre'] == '' ||
//     $_POST['apellido'] == '' ||
//     $_POST['nombreUsuario'] == '' ||
//     $_POST['numeroCedula'] == '' ||
//     $_POST['numeroCelular'] == '' ||
//     $_POST['fechaNacimiento'] == '' ||
//     $_POST['lugar_donde_vive'] == '' ||
//     $_POST['seleccion-carrera'] == '' ||
//     $_POST['tituloGraduado'] == ''
// ) {
//     $respuesta['mensaje'] = 'Datos vacios';
//     echo json_encode($respuesta);
//     die();
// }



// DATOS
$nombre = htmlspecialchars($_POST['nombre']);
$apellido = htmlspecialchars($_POST['apellido']);
$nombreUsuario = htmlspecialchars($_POST['nombreUsuario']);
$numeroCedula = htmlspecialchars($_POST['numeroCedula']);
$numeroCelular = htmlspecialchars($_POST['numeroCelular']);
$fechaNacimiento = htmlspecialchars($_POST['fechaNacimiento']);
$lugar_donde_vive = htmlspecialchars($_POST['lugar_donde_vive']);
$id_carrera = htmlspecialchars($_POST['seleccion-carrera']);
$id_aspirante = $_SESSION['id_usuario'];



// para la imagen por defecto 
$rutaDefectoImagne = "../../../imagenes/FotoperfilDefectoAspiranteHombre.jpg";





// existe una imagen
if ($_FILES['imagen']['size'] > 0) {

    // existe una imagen que el usuario cargo
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
} else {

    //si no existe una imagen se usa la ruta por defecto
    $imagen = addslashes(file_get_contents($rutaDefectoImagne));
}



// insertar datos en la tabla "datos_esrudiantes"
$respuestaInsertarDatosEstudiante = mysqli_query($conn, "INSERT INTO datos_estudiantes 
(nombre, apellido, imagen_perfil, 
nombreUsuario, cedula, numero_celular, 
fecha_nacimiento, lugar_donde_vive , 
fk_id_carrera, fk_id_usuEstudiantes) 
VALUES 
('$nombre', '$apellido', '$imagen', '$nombreUsuario', 
'$numeroCedula' ,'$numeroCelular', '$fechaNacimiento', 
'$lugar_donde_vive', '$id_carrera', '$id_aspirante')");


if ($respuestaInsertarDatosEstudiante) {

    // actualizar el fk en la tabal cedula para que otro aspirante no pueda usarla
    $respuestaActualizarCedula = mysqli_query($conn, "UPDATE cedula SET fk_id_usuEstudiantes = '$id_aspirante' WHERE cedula = '$numeroCedula' ");

    if ($respuestaActualizarCedula) {

        // este da el mensaje que todo salio bien
        $respuesta['mensaje'] = 'ok';
        session_destroy();
    } else {

        // error
        $respuesta['mensaje'] = 'nop';
    }

    //
} else {
    $respuesta['mensaje'] = 'error al registrar los datos del usuario';
}

echo json_encode($respuesta);
