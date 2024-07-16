<?php

if (!isset($_POST['id_postula'])) {
    echo "no puedes estar aqui";
    die();
}

include("../../conexion.php");


// datos
$id_postula = $_POST['id_postula'];
$id_oferta_trabajo = $_POST['id_oferta'];
$puesto = $_POST['puesto'];
$empresa = $_POST['empresa'];

// borrar la aprobacion de la oferta
$deletePostulacion  = mysqli_query($conn, "UPDATE postula SET aprobado = 0 WHERE id_postula = '$id_postula'");
while (mysqli_next_result($conn)) {;
}


// incrementar las plazas en la oferta
$queryIncrementarPlaza = mysqli_query($conn, "UPDATE oferta_trabajo SET plaza = plaza + 1, estado_oferta = 1 WHERE id_oferta_trabajo = $id_oferta_trabajo");
while (mysqli_next_result($conn)) {;
}



// correo para avisarle que lo desaprobaron
$para = $_POST['correo'];
$titulo = "Desaprobacion de tu solicitud en la oferta de empleo";
$mensaje = "¡Hola!\n\nLamentamos informarte que tu solicitud para el pusesto " . "'" . $puesto . "'" . " en la empresa " . $empresa . "ha sido desaprobada. Apreciamos tu interes y esfuerzo al postularte en nuestra plataforma digital.\n\nAunque en esta ocasión no logramos avanzar con tu aplicacion, te animamos a seguir explorando oportunidades laborales en nuestra plataforma. ¡No te desanimes! Estamos aqui para apoyarte en tu busqueda de empleo.\n\nGracias por confiar en nosotros.\n\nAtentamente,\nBolsa de Empleo Unesum";
$correoBolsaDeEmpleo = "From: soporte@trabajounesum.com";



if ($deletePostulacion && mail($para, $titulo, $mensaje, $correoBolsaDeEmpleo)) {

    echo json_encode(array('mensaje' => 'ok'));
} else {
    echo json_encode(array('mensaje' => 'nop'));
}
