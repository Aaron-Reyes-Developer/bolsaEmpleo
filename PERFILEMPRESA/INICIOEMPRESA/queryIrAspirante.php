<?php

if (isset($_POST['id'])) {

    session_start();

    $_SESSION['id_aspirante'] = $_POST['id'];

    echo json_encode(array('mensaje' => 'ok'));
}
