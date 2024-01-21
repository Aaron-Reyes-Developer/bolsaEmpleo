<?php

if (!isset($_POST['id_postula'])) {
    echo "no puedes estar aqui";
    die();
}

include("../../conexion.php");


// datos
$id_postula = $_POST['id_postula'];

// borrar la aprobacion de la oferta
$deletePostulacion  = mysqli_query($conn, "UPDATE postula SET aprobado = 0 WHERE id_postula = '$id_postula'");
while (mysqli_next_result($conn)) {;
}



// query para obtener los datos del nombre de la empresa y el nombre del puesto y el id de la oferta 
$queryDatosOferta = mysqli_query($conn, "SELECT 
datoEm.nombre, oft.puesto , 
oft.id_oferta_trabajo,
usuEs.correo
FROM postula post
INNER JOIN usuario_empresa usuEm
ON usuEm.id_usuario_empresa = post.fk_id_usuario_empresa
INNER JOIN datos_empresa datoEm
ON usuEm.id_usuario_empresa = datoEm.fk_id_usuario_empresa
INNER JOIN oferta_trabajo oft 
ON oft.id_oferta_trabajo = post.fk_id_oferta_trabajo
INNER JOIN usuario_estudiantes usuEs 
ON usuEs.id_usuEstudiantes = post.fk_id_usuEstudiantes
WHERE post.id_postula = $id_postula");
while (mysqli_next_result($conn)) {;
}


$rowDatos = mysqli_fetch_assoc($queryDatosOferta);
$id_oferta_trabajo = $rowDatos['id_oferta_trabajo'];
$puesto = $rowDatos['puesto'];
$empresa = $rowDatos['nombre'];



// incrementar las plazas en la oferta
$queryIncrementarPlaza = mysqli_query($conn, "UPDATE oferta_trabajo SET plaza = plaza + 1, estado_oferta = 1 WHERE id_oferta_trabajo = $id_oferta_trabajo");
while (mysqli_next_result($conn)) {;
}



// correo para avisarle que lo desaprobaron
$para = $rowDatos['correo'];
$titulo = "Desaprobación de tu solicitud en la oferta de empleo";
$mensaje = "¡Hola!\n\nLamentamos informarte que tu solicitud para el pusesto " . "'" . $puesto . "'" . " en la empresa " . $empresa . "ha sido desaprobada. Apreciamos tu interés y esfuerzo al postularte en nuestra plataforma digital.\n\nAunque en esta ocasión no logramos avanzar con tu aplicación, te animamos a seguir explorando oportunidades laborales en nuestra plataforma. ¡No te desanimes! Estamos aquí para apoyarte en tu búsqueda de empleo.\n\nGracias por confiar en nosotros.\n\nAtentamente,\nBolsa de Empleo Unesum";
$correoBolsaDeEmpleo = "From: bolsadeempleounesum@gmail.com";



if ($deletePostulacion && mail($para, $titulo, $mensaje, $correoBolsaDeEmpleo)) {

    echo json_encode(array('mensaje' => 'ok'));
} else {
    echo json_encode(array('mensaje' => 'nop'));
}
