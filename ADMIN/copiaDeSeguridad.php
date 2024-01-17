<?php

session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../index.html');
    die();
}


$servername = "localhost";
$username = "u855920239_aaronReyes";
$password = "0982746133Alulu-";
$database = "u855920239_bolsadetrabajo";

$respuesta = array('mensaje' => 'Estamos dentro');

// Ruta donde se guardará el archivo de respaldo
$backupPath = "C:/Users/reyes/Downloads/copias_de_seguridad_bd_bolsa_de_empleo/";

// Nombre del archivo de respaldo
$backupFilename = "backup_" . date("Y-m-d-H-i-s") . ".sql";

// Comando para hacer la copia de seguridad
$command = "mysqldump --user=$username --password=$password --routines --host=$servername $database > $backupPath$backupFilename";

// Ejecutar el comando
system($command, $output);



if ($output === 0) {
    $respuesta['mensaje'] = 'Copia de seguridad creada.';
} else {
    $respuesta['mensaje'] = 'Hubo un error al crear la copia de seguridad.';
}

echo json_encode($respuesta);
