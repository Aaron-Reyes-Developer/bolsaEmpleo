<?php

session_start();

//si no se encuentra el perfil
if (!isset($_POST['id_oferta'])) {
    header('Location: ./LOGIN/login.php');
    die();
}


$_SESSION['id_oferta'] = $_POST['id_oferta'];


if ($_SESSION['id_oferta']) {
    echo json_encode(array('mensaje' => 'ok'));
} else {
    echo json_encode(array('mensaje' => 'nop'));
}
