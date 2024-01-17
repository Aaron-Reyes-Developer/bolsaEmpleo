<?php

session_start();

// este codigo hace que el id_empresa este en un session, lo cual hace que podamos entrar a cualquier perfil de empresa
if (isset($_POST['id_empresa'])) {

    $_SESSION['id_empresa'] = $_POST['id_empresa'];

    echo json_encode(['mensaje' => 'ok']);
    die();
}

echo json_encode(['mensaje' => 'nop']);
