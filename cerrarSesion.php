<?php
    session_start();
    $dato = $_SESSION['ok'];

    //ocultar errores
    error_reporting(0);

    
    if($dato != null || $dato != ""){
        session_destroy();
        header('Location: ./LOGIN/login.php');
    }else{
        echo "Usted no tiene permitido ingresar a esta ubicacion";
    }

?>