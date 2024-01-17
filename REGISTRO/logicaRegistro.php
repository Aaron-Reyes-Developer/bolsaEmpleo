<?php

    // SI NO ENVIAN NADA EN EL FORMULARIO
    if(isset($_POST['seleccion-registro']) == ""){

        echo "<script>alert('Ingresa un tipo de registro') </script>";
        echo "<script> window.location.href = './registro.php'; </script>";
        die();
    }

    $tipoDeRegistro = htmlspecialchars($_POST['seleccion-registro']);
    
    if($_SESSION['seleccion-registro'] == "empresa"){

        header('Location: ./REGISTROEMPRESA/REGISTRO2/registro2.php');

    }else if($tipoDeRegistro == "aspirante"){
        echo "<script> alert('enviar al registro aspirante') </script>";
    }
