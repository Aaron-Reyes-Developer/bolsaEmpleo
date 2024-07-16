<?php
include('../conexion.php');
include('../funciones.php');

//ocultar los errores cunado se termine el proyecto 
// error_reporting(0);

session_start();

// eliminar la session de el id_oferta para que no halla ningun inconveniente
if (isset($_SESSION['id_oferta'])) {
    unset($_SESSION['id_oferta']);
}

// eliminar la session de la empresa
if (isset($_SESSION['id_empresa'])) {
    unset($_SESSION['id_empresa']);
}


$id_aspirante = $_SESSION['id_aspirantes'];


//si se intenta ingresar sin iniciar sesion
if ($id_aspirante == null) {
    header('Location: ../LOGIN/login.php');
    die();
}



//funcion para consultar todos los datos del aspirante (curriculum, experiencia, educacion, idioma, aptitudes, portafolio)
$queryMainAspirante = "call datosMainEstudiante('$id_aspirante')";
$respuestaMainAspirante = mysqli_query($conn, $queryMainAspirante);
$recorrerMainAspirante = mysqli_fetch_array($respuestaMainAspirante);
while (mysqli_next_result($conn)) {;
}


if (!$respuestaMainAspirante) {
    echo "error al consultar Datos Padre: ", mysqli_error($conn);
}


//mostrar los id de Experiencia  y educacion
$id_experiencia = $recorrerMainAspirante['id_experiencia'];
$id_educacion = $recorrerMainAspirante['id_educacion'];
$id_curriculum = $recorrerMainAspirante['id_curriculum'];


//DATOS FALTANTES
if (isset($_POST['guardar'])) {


    //datos educacion
    // $seleccion_educacion = $_POST['seleccion_educacion'];
    // $nombreInstitucion = $_POST['nombreInstitucion'];
    // $fecha_culminacion = $_POST['fecha_culminacion'];
    // $especialidad_carrera = $_POST['especialidad_carrera'];


    //datos curriculum
    $detalle_curriculum = $_POST['detalle_curriculum'];
    $habilidades = $_POST['habilidades'];
    $seleccion_estado_trabajo = $_POST['seleccion_estado_trabajo'];
    $especializacion_cv = $_POST['especializacion_curriculum'];
    $link_portafolio = $_POST['link_portafolio'];



    //////////////////////////INGRESAR CURRICULUM////////////////////////////////

    $queryInsertarCurriculum = "INSERT INTO curriculum (estado_trabajo, detalle_curriculum, habilidades, especializacion_curriculum ,portafolio, fk_id_usuEstudiantes) VALUES ('$seleccion_estado_trabajo', '$detalle_curriculum', '$habilidades', '$especializacion_cv' ,'$link_portafolio', '$id_aspirante')";
    $respuestaInsertarCurriculum = mysqli_query($conn, $queryInsertarCurriculum);

    if (!$respuestaInsertarCurriculum) {
        echo "error ingresar datos curriculum", mysqli_error($conn);
        die();
    }


    // hago una cunsulta porque no agarra el id_curriculum que viene desde la consulta 'call_datosMainAspirante'
    $queryIdCurriculum = "SELECT id_curriculum FROM curriculum WHERE fk_id_usuEstudiantes = '$id_aspirante' ";
    $IdCurriculum = mysqli_query($conn, $queryIdCurriculum);
    $recorrerIdCurriculum = mysqli_fetch_array($IdCurriculum);
    $id_curriculum = $recorrerIdCurriculum['id_curriculum'];



    //////////////////////////INGRESAR EXPERIENCIA////////////////////////////////

    //ingresar los datos de experiencia
    if (
        !isset($_POST['sinExperiencia']) &&
        $_POST['nombreEmpresa'] != "" &&
        $_POST['cargo'] != "" &&
        $_POST['tiempo'] != "" &&
        $_POST['seleccion_tiempo'] != ""
    ) {

        //datos experiecia
        $nombreEmpresa = $_POST['nombreEmpresa'];
        $cargo = $_POST['cargo'];
        $tiempo = $_POST['tiempo'];
        $seleccion_tiempo = $_POST['seleccion_tiempo'];

        $queryInsertarExperiencia = "INSERT INTO experiencia (nombre_empresa, cargo, tiempo_trabajo,fk_id_curriculum) VALUES ('$nombreEmpresa', '$cargo', '$tiempo $seleccion_tiempo', '$id_curriculum')";
        $respuestaInsertarExperiencia = mysqli_query($conn, $queryInsertarExperiencia);
        // while(mysqli_next_result($conn)){;}

        if (!$respuestaInsertarExperiencia) {
            echo "error ingresar datos en experiencia: ", mysqli_error($conn);
            die();
        }
    }


    //////////////////////////INGRESAR EDUCACION////////////////////////////////

    // // insertar
    // $queryInsertarEducacion = "INSERT INTO educacion (tipo, nombre_institucion, fecha_culminacion, especializacion, fk_id_curriculum) VALUES ('$seleccion_educacion', '$nombreInstitucion', '$fecha_culminacion', '$especialidad_carrera' , '$id_curriculum')";
    // $respuestaInsertarEducacion = mysqli_query($conn, $queryInsertarEducacion);


    // if (!$respuestaInsertarEducacion) {
    //     echo "error al ingresar datos a la educacion: ", mysqli_error($conn);
    //     die();
    // }



    ///////////////////////////INGRESAR IDIOMA//////////////////////////////// 

    if ((!empty($_POST['idioma']) &&  !empty($_POST['nivel'])) || ($_POST['idioma'] != "" && $_POST['nivel'] != "")) {

        // datos idioma
        $idioma = $_POST['idioma'];
        $nivel = $_POST['nivel'];


        // ingresar
        $queryIngresarIdioma = "INSERT INTO idioma (idioma, nivel, fk_id_curriculum) VALUES ('$idioma', '$nivel', '$id_curriculum') ";
        $respuestaIngresarIdioma = mysqli_query($conn, $queryIngresarIdioma);
        if (!$respuestaIngresarIdioma) {
            die("error en idioma: " . mysqli_error($conn));
        }
    }



    // saber si todo esta bien y mandar una alerta
    if ($respuestaInsertarCurriculum && $respuestaInsertarEducacion) {
?>

        <body>
            <!-- boostrap -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalTodoCorrecto.js"></script>

        </body>
    <?php
    }
}



// NOTIFICACION (icono campana)
$queryNotificacion = mysqli_query($conn, "call notificacion('$id_aspirante');");
$n_r_notificacion = mysqli_num_rows($queryNotificacion);
while (mysqli_next_result($conn)) {;
}


// desaparecer el numero de la notificacion
if (isset($_GET['notifiUpdate'])) {
    $queryActualizarNotificacion = mysqli_query($conn, "UPDATE postula SET estado_noti = '0' WHERE fk_id_usuEstudiantes = '$id_aspirante' AND estado_noti = 1 ");
    header('Location: ./perfilAspirante.php');
}



// ELIMINAR REFERENCIA
if (isset($_REQUEST['id_referencia'])) {

    $id_referencia = $_REQUEST['id_referencia'];

    $queryEliminarReferencia = mysqli_query($conn, "DELETE FROM referencia where id_referencia = '$id_referencia'");

    if ($queryEliminarReferencia) {
        header('Location: ./perfilAspirante.php');
    } else {
        echo "algo salio mal, recarga la pagina";
    }
}

// MODAL AVATAR
if (isset($_POST['guardarFoto'])) {


    $permitidos = array('image/jpg', 'image/png', 'image/gif', 'image/jpeg');
    $limite_kb = 100;

    $fotoModal = addslashes(file_get_contents($_FILES['fotoModal']['tmp_name']));

    $queryNuevaFoto = "UPDATE datos_estudiantes SET imagen_perfil = '$fotoModal' WHERE fk_id_usuEstudiantes = '$id_aspirante' ";
    $respuestaNuevaFoto = mysqli_query($conn, $queryNuevaFoto);

    if (!$respuestaNuevaFoto) {
        die(mysqli_error($conn));
    }

    header('Location: ./perfilAspirante.php');
}

// MODAL DATOS PERFIL
if (isset($_POST['guardarDatosPerfil'])) {

    // datos
    $nombreModal = htmlspecialchars($_POST['nombreModal']);
    $apellidoModal = htmlspecialchars($_POST['apellidoModal']);
    $fechaModal = htmlspecialchars($_POST['fechaModal']);
    $nombreUsuarioModal = htmlspecialchars($_POST['nombreUsuarioModal']);
    $numeroCelularModal = htmlspecialchars($_POST['numeroCelularModal']);
    $lugarViveModal = htmlspecialchars($_POST['lugarViveModal']);
    $especialidadModal = htmlspecialchars($_POST['especialidadModal']);

    // obtiene el valor dependiendo de que se rellene o no
    if ($_POST['nombreModal'] == "") {
        $nombreModal = $recorrerMainAspirante['nombre'];
    }
    if ($_POST['apellidoModal'] == "") {
        $apellidoModal = $recorrerMainAspirante['apellido'];
    }
    if ($_POST['fechaModal'] == "") {
        $fechaModal = $recorrerMainAspirante['fecha_nacimiento'];
    }
    if ($_POST['nombreUsuarioModal'] == "") {
        $nombreUsuarioModal = $recorrerMainAspirante['nombreUsuario'];
    }
    if ($_POST['numeroCelularModal'] == "") {
        $numeroCelularModal = $recorrerMainAspirante['numero_celular'];
    }
    if ($_POST['lugarViveModal'] == "") {
        $lugarViveModal = $recorrerMainAspirante['lugar_donde_vive'];
    }
    if ($_POST['tituloCarreraGraduadoModal'] == "") {
        $tituloCarreraGraduadoModal = $recorrerMainAspirante['tituloGraduado'];
    }
    if ($_POST['especialidadModal'] == "") {
        $especialidadModal = $recorrerMainAspirante['especializacion_curriculum'];
    }


    // actualizar datos aspirantes
    $queryActualizarDatos = "UPDATE datos_estudiantes SET nombre = '$nombreModal', apellido = '$apellidoModal', nombreUsuario = '$nombreUsuarioModal', numero_celular = '$numeroCelularModal', fecha_nacimiento = '$fechaModal', lugar_donde_vive = '$lugarViveModal'
    WHERE fk_id_usuEstudiantes = '$id_aspirante' ";
    $respuestaActualizarDatos = mysqli_query($conn, $queryActualizarDatos);
    while (mysqli_next_result($conn)) {;
    }

    // actualizar datos curriculum
    $queryActualizarDatosCurriculum = "UPDATE curriculum SET especializacion_curriculum = '$especialidadModal' WHERE fk_id_usuEstudiantes = '$id_aspirante' ";
    $respuestaActualizarDatosCurriculum = mysqli_query($conn, $queryActualizarDatosCurriculum);
    while (mysqli_next_result($conn)) {;
    }

    if (!$respuestaActualizarDatos) {
        die(mysqli_error($conn));
    }

    header('Location: ./perfilAspirante.php');
}

// MODAL ESTADO TRABAJO Y DETALLE DE ASPIRANTE
if (isset($_POST['guardarEstado'])) {

    if (($_POST['estadoModal'] == "") || ($_POST['descripcionPersonalModal'] == "")) {
        echo "<script> alert('Rellena todos los datos') </script>";
        echo "<script> window.location.href = './perfilAspirante.php' </script>";
        die();
    }

    $estadoModal = htmlspecialchars($_POST['estadoModal']);
    $descripcionPersonalModal = htmlspecialchars($_POST['descripcionPersonalModal']);

    $queryActualizarEstado = "UPDATE curriculum SET estado_trabajo = '$estadoModal', detalle_curriculum = '$descripcionPersonalModal' WHERE fk_id_usuEstudiantes = '$id_aspirante' ";
    $respuestaActualizarEstado = mysqli_query($conn, $queryActualizarEstado);

    if (!$respuestaActualizarEstado) {
        die(mysqli_error($conn));
    }

    header('Location: ./perfilAspirante.php');
}

// MODAL CONOCIMIENTO
if (isset($_POST['guardarConocimiento'])) {

    $nombreConocimiento = htmlspecialchars($_POST['nombreConocimiento']);

    // query agregar conocimiento
    $queryAgregarConocimiento = mysqli_query($conn, "INSERT INTO conocimientos (nombre_conocimiento, fk_id_curriculum) VALUES ('$nombreConocimiento', '$id_curriculum' )");


    if (!$queryAgregarConocimiento) {
        echo "error al ingresar concociminento: ", mysqli_error($conn);
    }

    header('Location: ./perfilAspirante.php');
}

// MODAL EXPERIENCIA
if (isset($_POST['guardarExperienciaModal'])) {

    $nombreInstitucionExperienciaModal = htmlspecialchars($_POST['nombreInstitucionExperienciaModal']);
    $nombreCargoExperienciaModal = htmlspecialchars($_POST['nombreCargoExperienciaModal']);
    $tareas_realizadas = htmlspecialchars($_POST['tareas_realizadas']);
    $duracionModalExperiencia = htmlspecialchars($_POST['duracionModalExperiencia']);
    $seleccion_experienciaModal = htmlspecialchars($_POST['seleccion_experienciaModal']);

    // query agregar experiencia
    $queryAgregarExperiencia = mysqli_query($conn, "INSERT INTO experiencia (nombre_empresa,cargo, tareas_realizadas, tiempo_trabajo, fk_id_curriculum) VALUES ('$nombreInstitucionExperienciaModal', '$nombreCargoExperienciaModal', '$tareas_realizadas' ,'$duracionModalExperiencia $seleccion_experienciaModal' ,'$id_curriculum' )");

    if (!$queryAgregarExperiencia) {
        echo "error al ingresar experiencia: ", mysqli_error($conn);
    }

    header('Location: ./perfilAspirante.php');
}

// MODAL REFERENCIA
if (isset($_POST['guardarReferenciaModal'])) {

    $nombre_referente = htmlspecialchars($_POST['nombre_referente']);
    $cargo_referente = htmlspecialchars($_POST['cargo_referente']);
    $numero_celular = htmlspecialchars($_POST['numero_celular']);
    $correo_referente = htmlspecialchars($_POST['correo_referente']);

    // query agregar experiencia
    $queryAgregarReferencia = mysqli_query($conn, "INSERT INTO referencia (nombre_referente,cargo_referente, numero_celular, correo_referente, fk_id_curriculum) VALUES ('$nombre_referente', '$cargo_referente', '$numero_celular' ,'$correo_referente', '$id_curriculum' )");

    if (!$queryAgregarExperiencia) {
        echo "error al ingresar experiencia: ", mysqli_error($conn);
    }

    header('Location: ./perfilAspirante.php');
}

// MODAL EDUCACION
if (isset($_POST['guardarEdudacionModal'])) {

    $seleccion_educacion_modal = htmlspecialchars($_POST['seleccion_educacion_modal']);
    $nombreInstitucionModal = htmlspecialchars($_POST['nombreInstitucionModal']);
    $nombreEspecializacion = htmlspecialchars($_POST['nombreEspecializacion']);
    $fecha_culminacion = htmlspecialchars($_POST['fecha_culminacion']);


    // query agregar educacion
    $queryAgregarEducacion = mysqli_query($conn, "INSERT INTO educacion (tipo, nombre_institucion, fecha_culminacion, especializacion, fk_id_curriculum) VALUES ('$seleccion_educacion_modal','$nombreInstitucionModal','$fecha_culminacion','$nombreEspecializacion','$id_curriculum')");


    // si algo sale mal
    if (!$queryAgregarEducacion) {

        echo "error al ingresar Eduacacion: ", mysqli_error($conn);
    }

    header("Location: ./perfilAspirante.php");
}

// MODAL IDIOMA
if (isset($_POST['guardarIdiomaModal'])) {

    $idiomaModal = htmlspecialchars($_POST['idiomaModal']);
    $nivelModal = htmlspecialchars($_POST['nivelModal']);

    if (($idiomaModal == "") || ($nivelModal == "")) {
        die("Datos Incompletos, Regresa.");
    }

    // insertar datos Idioma
    $queryInsertarIdioma = "INSERT INTO idioma (idioma, nivel, fk_id_curriculum) VALUES ('$idiomaModal', '$nivelModal',  '$id_curriculum' );";
    $respuestaInsertarIdioma = mysqli_query($conn, $queryInsertarIdioma);

    if (!$respuestaInsertarIdioma) {
        echo "error al ingresar concociminento: ", mysqli_error($conn);
        die();
    }

    header('Location: ./perfilAspirante.php');
}

// MODAL PROTAFOLIO
if (isset($_POST['guardarPortafolioModal'])) {

    $UrlPortafolioModal = $_POST['UrlPortafolioModal'];


    $queryActualizarPortafolio = mysqli_query($conn, "UPDATE curriculum SET portafolio = '$UrlPortafolioModal' WHERE fk_id_usuEstudiantes = '$id_aspirante' ");

    // si algo sale mal
    if (!$queryActualizarPortafolio) {

        echo "error al ingresar portafolio: ", mysqli_error($conn);
    }

    header("Location: ./perfilAspirante.php");
}

// MODAL CERTIFICADO
if (isset($_POST['guardarCertificadoModal'])) {

    if ($_POST['UrlCertificadoModal'] == "") {
        echo "dato vacio";
        die();
    }

    $urlCertificadoModal = htmlspecialchars($_POST['UrlCertificadoModal']);

    // query actualizar certificados
    $queryInsertarCertificado = mysqli_query($conn, "UPDATE curriculum SET otrosLinks = '$urlCertificadoModal' WHERE id_curriculum = '$id_curriculum' ");
}

// MODAL NUEVA CONTRASEÑA
if (isset($_POST['guardarContraseñaModal'])) {

    if (empty($_POST['nuevaContraseñaModal']) || $_POST['nuevaContraseñaModal'] == "") {
        echo "<script>  alert('Dato Vacio') </script>";
        die();
    }

    // dato nueva contraseña
    $nuevaContra = hash('ripemd160', $_POST['nuevaContraseñaModal']);


    // actulizar la contraseña
    $queryActualizarContra = mysqli_query($conn, "UPDATE usuario_estudiantes SET contra = '$nuevaContra' WHERE id_usuEstudiantes = '$id_aspirante' ");


    // borramos la contraseña temporal
    $queryActualizarContraTemporal = mysqli_query($conn, "UPDATE usuario_estudiantes SET contra_temporal = NULL WHERE id_usuEstudiantes = '$id_aspirante'  ");


    if ($queryActualizarContra) {
    ?>

        <body>
            <!-- boostrap -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalTodoCorrecto.js"></script>

        </body>
<?php
    }
}





?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imagenes/iconos/iconoAdmin/iconoPaginas.gif">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- FUENTE DE FONT GOOGLE -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital@1&display=swap" rel="stylesheet">

    <!-- ANIMACION LIBRERIA -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


    <!-- ICONOS FAST AWESOME -->
    <script src="https://kit.fontawesome.com/530f126b4a.js" crossorigin="anonymous"></script>

    <!-- alerta personalizada -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">


    <link rel="stylesheet" href="estiloPerfilAspirante.css">
    <title>Aspirante</title>
</head>

<body>

    <!-- para mostrar el avatar en toda la pantall -->
    <div class="contenedorVerAvatar" id="contenedorVerAvatar"></div>


    <header class="">

        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../index.html">
                    <img src="../imagenes/logoUnesum.png" alt="">
                </a> -->

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav">

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../COMENTARIOS/comentarios.php"><img src="../imagenes/Iconos/comentarios.png" alt="Comentarios" title="Comentarios"></a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./INICIO/inicio.php"><img src="../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../ENCONTRETRABAJO/encontreTrabajo.php"><img src="../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa iconoCampana">

                            <?php

                            if ($n_r_notificacion >= 1) {


                            ?>
                                <div class="dropdown">

                                    <!-- icono campana con la notificacion -->
                                    <a class="iconoEnlace" data-bs-toggle="dropdown" aria-expanded="false">

                                        <img src="../imagenes/Iconos/campana.svg" alt="">

                                        <div class="bg-danger rounded text-light numero_icono">
                                            <?php echo $n_r_notificacion ?>
                                        </div>
                                    </a>

                                    <ul class="dropdown-menu">

                                        <?php

                                        while ($recorrerNoti = mysqli_fetch_array($queryNotificacion)) {

                                        ?>
                                            <li>
                                                <a class="dropdown-item notificacionTexto" onclick="irOferta(<?php echo $recorrerNoti['id_oferta_trabajo'] ?>, <?php echo $recorrerNoti['id_usuario_empresa'] ?>)">
                                                    <?php echo "Aprobado en: " . "<b>" . $recorrerNoti['puesto'] . "</b>" ?>
                                                </a>
                                            </li>

                                        <?php
                                        }
                                        ?>

                                        <hr>
                                        <li><a class="dropdown-item marcarComoLeido" href="?notifiUpdate=ok">Marcar como leido</a></li>
                                        <li><a class="dropdown-item" href="../VERNOTIFICACIONES/verNotificaciones.php">Ver todas las notifi...</a></li>
                                    </ul>
                                </div>
                            <?php


                            } else {
                            ?>
                                <div class="dropdown">

                                    <!-- icono campana con la notificacion -->
                                    <a class="iconoEnlace" data-bs-toggle="dropdown" aria-expanded="false">

                                        <img src="../imagenes/Iconos/campana.svg" alt="">
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item">Sin Notificaciones 😥</a></li>
                                        <hr>
                                        <li><a class="dropdown-item" href="../VERNOTIFICACIONES/verNotificaciones.php">Ver todas la notificaciones...</a></li>
                                    </ul>
                                </div>



                            <?php
                            }


                            ?>

                        </li>

                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../cerrarSesion.php"><img src="../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav" style="width: 50px; height: 50px;">
                            <a class="nav-link enlace-avatar" aria-current="page" style="padding: 0;"><img style="width: 100%; height: 100%; object-fit: cover;" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerMainAspirante['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="main">


        <!-- SECCION PERFIL -->
        <section class="seccionPerfil">

            <div class="contenedorDatosPerfil">

                <!-- AVATAR -->
                <div class="protada">

                    <img src="../imagenes/portadaDefectoPerfil.jpg" alt="">

                    <div class="dropdown">

                        <div class="botonDown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="iconoConfiguracion" src="../imagenes/Iconos/iconoConfiguracion.png" alt="Configuracion" title="Icono sacado de: www.flaticon.es">
                        </div>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleContraseña" data-bs-whatever="@mdo">Cambiar Contraseña</a></li>
                            <li><a class="dropdown-item" href="">En desarrollo</a></li>
                            <li><a class="dropdown-item" href="#">En desarrollo</a></li>
                        </ul>
                    </div>



                    <!-- avatar imagen -->
                    <div class="contenedor-avatar">

                        <img onclick="verAvar()" id="imagenPerfil" class="imagenPerfil" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerMainAspirante['imagen_perfil']) ?>" alt="">

                        <a href="#" class="editarAvatar" data-bs-toggle="modal" data-bs-target="#exampleAvatar" data-bs-whatever="@mdo">&#x270e</a>
                    </div>


                </div>


                <!-- DATOS -->
                <div class="datosPerfil mt-5 mb-4">
                    <div class="informacionPerfil">
                        <h3><?php echo $recorrerMainAspirante['nombre'], " ", $recorrerMainAspirante['apellido'] ?> <a href="#" class="editarPerfil" class="" data-bs-toggle="modal" data-bs-target="#exampleDatosPerfil" data-bs-whatever="@mdo">&#x270e</a></h3>
                        <span><?php echo $recorrerMainAspirante['tituloGraduado'] ?></span> <br>
                        <span>Especialidad: <?php echo $recorrerMainAspirante['especializacion_curriculum'] ?></span> <br>
                        <span>Edad: <?php echo calcularEdad($recorrerMainAspirante['fecha_nacimiento'])  ?></span> <br>
                        <span>@<?php echo $recorrerMainAspirante['nombreUsuario'] ?></span> <br>
                        <span>Contacto: <?php echo $recorrerMainAspirante['correo'] ?></span> <br>
                        <span>Número Celular: <a target="_blank" href="https://wa.me/+593<?php echo $recorrerMainAspirante['numero_celular'] ?>"><?php echo $recorrerMainAspirante['numero_celular'] ?></a></span> <br>
                        <span>Número de Cedula: <?php echo $recorrerMainAspirante['cedula'] ?></span> <br>
                        <span><?php echo $recorrerMainAspirante['lugar_donde_vive'] ?></span>
                    </div>
                </div>

                <!-- ESTADO -->
                <div class="estadoPerfil">
                    <h4>
                        <?php
                        if ($recorrerMainAspirante['estado_trabajo'] != null || $recorrerMainAspirante['estado_trabajo'] != "") {
                            echo $recorrerMainAspirante['estado_trabajo'];
                        } else {
                            echo '<i>Aquí saldra tu estado de empleo</i>';
                        }

                        ?>
                    </h4>


                    <span>

                        <?php

                        if ($recorrerMainAspirante['detalle_curriculum'] != null || $recorrerMainAspirante['detalle_curriculum'] != "") {
                            echo $recorrerMainAspirante['detalle_curriculum'];
                        } else {
                            echo 'Aquí saldrá un resumen de tu descripción como persona';
                        }

                        ?>

                    </span>

                    <?php

                    if ($recorrerMainAspirante['detalle_curriculum'] != null || $recorrerMainAspirante['detalle_curriculum'] != "") {
                    ?>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleEstado" data-bs-whatever="@mdo">Editar...</a>
                    <?php
                    }

                    ?>


                </div>

            </div>

            <!-- IMPRIMIR PDF -->
            <?php
            if ($recorrerMainAspirante['id_curriculum'] != null) {
            ?>

                <div class="contenedorBotones">

                    <div class="aprobar imprimir">
                        <a onclick="irImprimir(<?php echo $id_aspirante ?>)" Target="_blank"><img src="../imagenes/Iconos/imprimir.png" alt="" title="Imprimir Cv"></a>
                    </div>

                </div>
            <?php
            }
            ?>


        </section>

        <!-- DATOS FALTANTES -->
        <section class="datosFaltantes p-3 <?php if ($recorrerMainAspirante['id_curriculum'] != null || $recorrerMainAspirante['id_curriculum'] != "") echo 'formularioDesaparecer' ?>">

            <div class="tituloDatosFaltantes">
                <h4>Rellena tus datos faltantes</h4>
            </div>

            <div class="contenedorFormularios ">

                <div class="row subContenedorFormularios">

                    <div class="col-xl-12 col-lg-12  mb-12 ">

                        <!-- FORMULARIO -->
                        <form method="post" class="p-2 formulario needs-validation" novalidate>

                            <div class="contenedor-inputsFaltantes">

                                <!-- EXPERIENCIA -->
                                <div class="contenedor-inputs">

                                    <h5>Experiencia</h5>
                                    <hr>

                                    <!-- SIN EXPERIENCIA -->
                                    <div class="form-floating mb-3 row has-validation">

                                        <div class="col-6 has-validation">
                                            <input type="checkbox" class="form-check-input" name="sinExperiencia" id="sinExperiencia">
                                            <label class="form-check-label" for="sinExperiencia">Sin Experiencia</label>
                                        </div>

                                    </div>

                                    <!-- Nombre de trabajo -->
                                    <div class="form-floating mb-3 has-validation">
                                        <input type="text" name="nombreEmpresa" class="form-control input-experiencia" id="floatingInput" placeholder="Nombre de la empresa">
                                        <label for="floatingInput" class="labelExperiencia">Nombre de la empresa</label>
                                    </div>


                                    <!-- CARGO -->
                                    <div class="form-floating mb-3 has-validation">
                                        <input type="text" name="cargo" class="form-control input-experiencia" id="floatingPassword" placeholder="Cargo en el que trabajaba">
                                        <label for="floatingPassword" class="labelExperiencia">Cargo en el que trabajabo</label>
                                    </div>


                                    <!-- TIEMPO -->
                                    <div class="form-floating mb-3 row has-validation">

                                        <div class="col-9">
                                            <input type="number" name="tiempo" class="form-control input-experiencia" id="floatingPassword" placeholder="Tiempo de trabajo" maxlength="3">


                                        </div>

                                        <div class="col-3 has-validation">
                                            <select class="form-select col-3" name="seleccion_tiempo" aria-label="Default select example">
                                                <option selected value="">Año/mes </option>
                                                <option value="Años">Años</option>
                                                <option value="Meses">Meses</option>
                                            </select>


                                        </div>
                                    </div>

                                </div>



                                <!-- CURRICULUM -->
                                <div class="contenedor-inputs">

                                    <h5>Curriculum</h5>
                                    <hr>

                                    <!-- Detalle personal   -->
                                    <div class="mb-3 has-validation">
                                        <label for="detalle_curriculum" class="form-label">Detalle Personal*</label>
                                        <textarea class="form-control" name="detalle_curriculum" id="detalle_curriculum" rows="3" placeholder="Sea Breve" required maxlength="500"></textarea>

                                        <div class="invalid-feedback">
                                            Ingresa tu detalle personal
                                        </div>
                                    </div>


                                    <!-- Habilidades   -->
                                    <div class="mb-3 has-validation">
                                        <label for="habilidades" class="form-label">Habilidades*</label>
                                        <textarea class="form-control" name="habilidades" id="habilidades" rows="3" placeholder="ej: Fuerte conocimientos en exel, Base de datos, etc..." required></textarea>

                                        <div class="invalid-feedback">
                                            Ingresa tus Habilidades
                                        </div>
                                    </div>

                                    <!-- Especializacion -->
                                    <div class="mb-3">
                                        <label for="especializacion" class="form-label">Especialización*</label><br>
                                        <input type="text" name="especializacion_curriculum" id="especializacion" class="form-control" placeholder="ej: Progrador Web" aria-label="Username" required>
                                    </div>


                                    <!-- IDIOMAS -->
                                    <label for="idiomas" class="form-label">Idioma</label><br>
                                    <div class="input-group mb-3">

                                        <input type="text" name="idioma" id="idiomas" class="form-control" placeholder="Idioma" aria-label="Username">
                                        <input type="text" name="nivel" class="form-control" placeholder="Nivel" aria-label="Server">
                                    </div>

                                    <!-- ESTADO TRABAJO   -->
                                    <div class="form-floating mb-3 has-validation">

                                        <div class="">
                                            <label for="" class="labelExperiencia">Estado de empleo*</label>

                                            <select class="form-select col-3" name="seleccion_estado_trabajo" aria-label="Default select example" required>
                                                <option selected value="">Selecciona un estado</option>

                                                <?php
                                                $queryEstadoEmpleo = mysqli_query($conn, "SELECT * FROM tipo_estado_trabajo WHERE estado = 1");
                                                while ($recorrerEstadoEmpleo = mysqli_fetch_assoc($queryEstadoEmpleo)) {
                                                ?>
                                                    <option value="<?php echo $recorrerEstadoEmpleo['nombre'] ?>"><?php echo $recorrerEstadoEmpleo['nombre'] ?></option>
                                                <?php
                                                }

                                                ?>

                                            </select>


                                        </div>
                                    </div>

                                    <!-- Portafolio   -->
                                    <div class="form-floating mb-3">
                                        <input type="text" name="link_portafolio" class="form-control input-experiencia" id="floatingInput" placeholder="Nombre de la empresa">
                                        <label for="floatingInput" class="labelExperiencia">Link de tu portafolio (no rellenar si no tienes uno)</label>
                                    </div>

                                </div>

                            </div>



                            <input type="submit" name="guardar" value="Guardar" class="btn btn-primary boton_formulario">

                        </form>

                    </div>

                </div>

            </div>

        </section>

        <!-- EXPERIENCIA -->
        <section class="seccionExperiencia">

            <!-- titulo y modal  -->
            <h4>
                Experiencia

                <!-- MODAL -->

                <?php

                // se pregunta si el estado esta lleno o no viene vacio, esto hace que si o si ya se halla rellenado los datos faltantes 
                if ($recorrerMainAspirante['estado_trabajo'] != null || $recorrerMainAspirante['estado_trabajo'] != "") {
                ?>
                    <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#exampleModalExperiencia" data-bs-whatever="@mdo">+</a>
                <?php
                }
                ?>


                <div class="modal fade" id="exampleModalExperiencia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <!-- titulo -->
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Experiencia</h1>
                            </div>


                            <!-- formulario Experiencia MODAL-->
                            <div class="modal-body">

                                <form method="post" class="formulario_agregar_experiencia form">

                                    <!-- nombre institucion -->
                                    <div class="mb-3">
                                        <label for="recipient-name" class="col-form-label" style="font-size: 1rem;">Nombre de la Institucion:</label>
                                        <input type="text" name="nombreInstitucionExperienciaModal" class="form-control" id="recipient-name" required>
                                    </div>


                                    <!-- cargo -->
                                    <div class="mb-3">
                                        <label for="cargo" class="col-form-label" style="font-size: 1rem;">Cargo:</label>
                                        <input type="text" name="nombreCargoExperienciaModal" class="form-control" id="cargo" required>
                                    </div>


                                    <!-- tareas realizadas -->
                                    <div class="mb-3">
                                        <label for="tareasRealizadas" class="form-label" style="font-size: 1rem;">Tareas Realizadas*</label>
                                        <textarea class="form-control" name="tareas_realizadas" id="tareasRealizadas" rows="3" required></textarea>
                                    </div>


                                    <!-- TIEMPO -->
                                    <div class="form-floating mb-3 row has-validation">

                                        <!-- tiempo  -->
                                        <div class="col-9">
                                            <input type="number" name="duracionModalExperiencia" class="form-control input-experiencia" id="floatingPassword" placeholder="Tiempo de trabajo" maxlength="3" required>
                                        </div>

                                        <!-- seleccion años/meses -->
                                        <div class="col-3 has-validation">
                                            <select class="form-select col-3" name="seleccion_experienciaModal" aria-label="Default select example" required>
                                                <option selected value="">Año/mes </option>
                                                <option value="Años">Años</option>
                                                <option value="Meses">Meses</option>
                                            </select>


                                        </div>
                                    </div>




                                    <button type="submit" name="guardarExperienciaModal" class="btn guardarModal  btn-primary">Guardar</button>
                                </form>
                            </div>


                            <!-- boton cerra modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                            </div>

                        </div>
                    </div>

                </div>

            </h4>

            <!-- verificamos si tiene experiencia-->
            <?php

            //hacemos una consulta solo para mostrar los datos de las experiencias que tiene
            $resultadoConsultaExperiencias = mysqli_query($conn, "SELECT * FROM experiencia WHERE fk_id_curriculum = '$id_curriculum' ");


            while ($mostrarDatosExperiencia = mysqli_fetch_array($resultadoConsultaExperiencias)) {

                if ($mostrarDatosExperiencia['nombre_empresa'] != null || $mostrarDatosExperiencia['nombre_empresa'] != "") {
            ?>

                    <!-- carta experiencia -->
                    <div class="contendor-experiencia">

                        <div class="contenedor-imagen-experiencia">
                            <img src="../imagenes/Iconos/experiencia.png" alt="">
                            <a href="../ELIMINACIONES/ASPIRANTE/ELIMINAREXPERIENCIA/eliminarExperiencia.php?id_experiencia=<?php echo $mostrarDatosExperiencia['id_experiencia'] ?>" class="btn-close botonEliminar botonEliminarExperiencia" aria-label="Close"></a>
                        </div>

                        <div class="contenedor-texto-experiencia">
                            <h5><?php echo $mostrarDatosExperiencia['cargo'] ?></h5>
                            <p style="color: #05b14c;"> <b>Establecimiento:</b> <?php echo $mostrarDatosExperiencia['nombre_empresa'] ?></p>
                            <span> <b>Duracion:</b> <?php echo $mostrarDatosExperiencia['tiempo_trabajo'] ?></span><br>
                            <span> <b>Tareas Realizadas:</b> <?php echo $mostrarDatosExperiencia['tareas_realizadas'] ?></span>
                        </div>

                    </div>

            <?php
                }
            }

            ?>

            <hr>

            <!-- referencias -->
            <h5 class="tituloReferencia">
                Referencias <i style="font-size: 1rem;">(personales)</i>

                <!-- MODAL -->
                <?php

                // se pregunta si el estado esta lleno o no viene vacio, esto hace que si o si ya se halla rellenado los datos faltantes 
                if ($recorrerMainAspirante['estado_trabajo'] != null || $recorrerMainAspirante['estado_trabajo'] != "") {
                ?>
                    <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#exampleModalReferencia" data-bs-whatever="@mdo">+</a>
                <?php
                }
                ?>


                <div class="modal fade" id="exampleModalReferencia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <!-- titulo -->
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Referencia ( <i>personal</i> )</h1>
                            </div>


                            <!-- formulario Experiencia MODAL-->
                            <div class="modal-body">

                                <form method="post" class="formulario_agregar_experiencia form">

                                    <!-- nombre referencia -->
                                    <div class="mb-3">
                                        <label for="nombre_referente" class="col-form-label" style="font-size: 1rem;">Nombre del referente*</label>
                                        <input type="text" name="nombre_referente" class="form-control" id="nombre_referente" required>
                                    </div>


                                    <!-- cargo -->
                                    <div class="mb-3">
                                        <label for="cargo_referente" class="col-form-label" style="font-size: 1rem;">Cargo del referente*</label>
                                        <input type="text" name="cargo_referente" class="form-control" id="cargo_referente" required>
                                    </div>


                                    <!-- celular -->
                                    <div class="mb-3">
                                        <label for="numero_celular" class="form-label" style="font-size: 1rem;">Numero Celular*</label>
                                        <input type="number" name="numero_celular" class="form-control" id="numero_celular" required>
                                    </div>


                                    <!-- correo -->
                                    <div class="mb-3">
                                        <label for="correo_referente" class="form-label" style="font-size: 1rem;">Correo*</label>
                                        <input type="email" name="correo_referente" class="form-control" id="correo_referente" required>
                                    </div>

                                    <button type="submit" name="guardarReferenciaModal" class="btn guardarModal  btn-primary">Guardar</button>
                                </form>
                            </div>


                            <!-- boton cerra modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                            </div>

                        </div>
                    </div>

                </div>

            </h5>


            <div class="subContenedorReferencia">

                <?php
                // muestra las referencias si ya se relleno los datos basicos
                if ($recorrerMainAspirante['id_curriculum'] != null) {


                    $queryReferencia = mysqli_query($conn, "SELECT * FROM referencia WHERE fk_id_curriculum  = $id_curriculum ");
                    while ($rowReferencia = mysqli_fetch_assoc($queryReferencia)) {
                ?>

                        <div class="cartaReferencia">
                            <p><b><?php echo $rowReferencia['nombre_referente'] ?></b></p>
                            <p class="ocupacion_referencia"><?php echo $rowReferencia['cargo_referente'] ?></p>
                            <p><a target="_blank" href="https://wa.me/+593<?php echo $rowReferencia['numero_celular'] ?>"><?php echo $rowReferencia['numero_celular'] ?></a></p>
                            <p><?php echo $rowReferencia['correo_referente'] ?></p>

                            <a href="?id_referencia=<?php echo $rowReferencia['id_referencia'] ?>" class="eliminarReferencia">X</a>
                        </div>

                <?php

                    }
                }
                ?>




            </div>


        </section>

        <!-- Educacion -->
        <section class="seccionEducacion">

            <h4>Educacion



                <?php

                // se pregunta si el estado esta lleno o no viene vacio, esto hace que si o si ya se halla rellenado los datos faltantes 
                if ($recorrerMainAspirante['estado_trabajo'] != null || $recorrerMainAspirante['estado_trabajo'] != "") {
                ?>
                    <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#exampleEducacion" data-bs-whatever="@mdo">+</a>
                <?php
                }

                ?>


                <div class="modal fade" id="exampleEducacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <!-- titulo -->
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Educacion</h1>
                            </div>


                            <!-- formulario Experiencia MODAL-->
                            <div class="modal-body">

                                <form method="post" class="formulario_agregar_experiencia form">


                                    <!-- tipo educacion -->
                                    <div class="form-floating mb-3 has-validation">

                                        <div class="">
                                            <label for="" class="labelExperiencia mb-3">Tipo de educacion</label>

                                            <select class="form-select col-3" name="seleccion_educacion_modal" aria-label="Default select example" required>
                                                <option selected value="">Elije el tipo de educacion</option>
                                                <option value="Universitaria">Universitaria</option>
                                                <option value="Colegio">Colegio</option>
                                                <option value="Curso">Cursos</option>
                                            </select>


                                        </div>
                                    </div>

                                    <!-- nombre institucion -->
                                    <div class="mb-3">
                                        <label for="nombreInstitucionModal" class="col-form-label" style="font-size: 1rem;">Nombre Institución*</label>
                                        <input type="text" name="nombreInstitucionModal" class="form-control" id="nombreInstitucionModal" required>
                                    </div>


                                    <!-- nombre especializacion -->
                                    <div class="mb-3">
                                        <label for="nombreEspecializacion" class="col-form-label" style="font-size: 1rem;">Nombre Especialización*</label>
                                        <input type="text" name="nombreEspecializacion" class="form-control" id="nombreEspecializacion" placeholder="Ej: Diseñador Web" required>
                                    </div>


                                    <!-- fecha culminacion-->
                                    <div class="mb-3">
                                        <label for="fecha_culminacion" class="col-form-label" style="font-size: 1rem;">Fecha Culminación*</label>
                                        <input type="date" name="fecha_culminacion" class="form-control" id="fecha_culminacion" required>
                                    </div>


                                    <button type="submit" name="guardarEdudacionModal" class="btn guardarModal  btn-primary">Guardar</button>

                                </form>

                            </div>


                            <!-- boton cerra modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                            </div>

                        </div>
                    </div>

                </div>

            </h4>


            <!-- verificamos si tiene educacion-->
            <?php

            //hacemos una consulta solo para mostrar los datos de la educacion que tiene
            $quieryConsultaEducacion = "SELECT * FROM educacion WHERE fk_id_curriculum = '$id_curriculum' ";
            $resultadoConsultaEducacion = mysqli_query($conn, $quieryConsultaEducacion);


            while ($mostrarDatosEducacion = mysqli_fetch_array($resultadoConsultaEducacion)) {

                if ($mostrarDatosEducacion['id_educacion'] != null || $mostrarDatosEducacion['id_educacion'] != " ") {

            ?>

                    <!-- Carta eduacacion -->
                    <div class="contendor-educacion">

                        <div class="contenedor-imagen-educacion">
                            <img src="../imagenes/Iconos/educacion.png" alt="">

                            <?php $_SESSION['okEliminar'] = 'okEliminar'    ?>

                            <a href="../ELIMINACIONES/ASPIRANTE/ELIMINAREDUCACION/eliminarEducacion.php?id_educacion=<?php echo $mostrarDatosEducacion['id_educacion'] ?>" class="btn-close botonEliminar botonEliminarEducacion" aria-label="Close"></a>
                        </div>

                        <div class="contenedor-texto-educacion">
                            <h5><?php echo $mostrarDatosEducacion['nombre_institucion'] ?></h5>
                            <p><?php echo "<b>Especializacion: </b>", $mostrarDatosEducacion['especializacion']  ?> </p>
                            <span><?php echo "<b>Fecha de culminacion: </b>", $mostrarDatosEducacion['fecha_culminacion'] ?></span>
                        </div>
                    </div>


            <?php

                }
            }

            ?>



        </section>

        <!-- Idiomas -->
        <section class="seccionEducacion seccionIdioma">

            <h4>Idiomas

                <!-- MODAL -->

                <?php

                // se pregunta si el estado esta lleno o no viene vacio, esto hace que si o si ya se halla rellenado los datos faltantes 
                if ($recorrerMainAspirante['estado_trabajo'] != null || $recorrerMainAspirante['estado_trabajo'] != "") {
                ?>
                    <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#exampleIdioma" data-bs-whatever="@mdo">+</a>
                <?php
                }

                ?>



                <div class="modal fade" id="exampleIdioma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <!-- titulo -->
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Idioma</h1>
                            </div>


                            <!-- formulario Idioma MODAL-->
                            <div class="modal-body">

                                <form method="post" class="formulario_agregar_experiencia form">


                                    <!-- idioma -->
                                    <div class="form-floating mb-3 has-validation">

                                        <div class="">
                                            <label for="" class="labelExperiencia mb-3" style="font-size: 1rem;">Idioma*</label>
                                            <input type="text" name="idiomaModal" class="form-control" id="nombreInstitucionModal" required>
                                        </div>
                                    </div>

                                    <!-- Nivel Idioma -->
                                    <div class="form-floating mb-3 has-validation">

                                        <div class="">
                                            <label for="" class="labelExperiencia mb-3" style="font-size: 1rem;">Nivel*</label>
                                            <input type="text" name="nivelModal" class="form-control" id="nombreInstitucionModal" required>
                                        </div>
                                    </div>


                                    <button type="submit" name="guardarIdiomaModal" class="btn guardarModal  btn-primary">Guardar</button>

                                </form>

                            </div>


                            <!-- boton cerra modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                            </div>

                        </div>
                    </div>

                </div>

            </h4>


            <?php

            //consulta para mostrar los idiomas
            $quieryConsultaIdioma = "SELECT id_idioma, idioma, nivel FROM idioma WHERE fk_id_curriculum = '$id_curriculum' ";
            $resultadoConsultaIdioma = mysqli_query($conn, $quieryConsultaIdioma);


            while ($mostrarDatosIdioma = mysqli_fetch_array($resultadoConsultaIdioma)) {

            ?>

                <!-- Carta eduacacion -->
                <div class="contendor-educacion">

                    <div class="contenedor-imagen-educacion">
                        <img src="../imagenes/logoIdioma.png" alt="">
                        <a href="../ELIMINACIONES/ASPIRANTE/ELIMINARIDIOMA/eliminarIdioma.php?id_idioma=<?php echo $mostrarDatosIdioma['id_idioma'] ?>" class="btn-close botonEliminar botonEliminarIdioma" aria-label="Close"></a>
                    </div>

                    <div class="contenedor-texto-educacion">
                        <h5><?php echo $mostrarDatosIdioma['idioma'] ?></h5>
                        <p><?php echo $mostrarDatosIdioma['nivel'] ?></p>

                    </div>
                </div>


            <?php

            }
            ?>

        </section>


        <!-- CONOCIMIENTOS Y APTITUDES -->
        <section class="seccionConocimientoAptitudes">

            <div class="tituloConocimiento">

                <h4>

                    Conocimientos y Aptitudes

                    <!-- MODAL -->

                    <?php

                    // se pregunta si el estado esta lleno o no viene vacio, esto hace que si o si ya se halla rellenado los datos faltantes 
                    if ($recorrerMainAspirante['estado_trabajo'] != null || $recorrerMainAspirante['estado_trabajo'] != "") {
                    ?>
                        <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">+</a>
                    <?php
                    }

                    ?>




                </h4>

            </div>

            <div class="contenedor-conocimiento">

                <?php
                $queryConsultarConocimiento = "SELECT * FROM conocimientos WHERE fk_id_curriculum = '$id_curriculum' GROUP BY id_conocimientos DESC  ";
                $respuestaConocimiento = mysqli_query($conn, $queryConsultarConocimiento);


                while ($recorrerConocimiento = mysqli_fetch_array($respuestaConocimiento)) {

                    // comprovar si existe algun datos 
                    if ($recorrerConocimiento['id_conocimientos'] != null || $recorrerConocimiento['id_conocimientos'] != " ") {
                ?>

                        <div class="conocimiento">
                            <a href="../ELIMINACIONES/ASPIRANTE/ELIMINARCONOCIMIENTO/eliminarConocimiento.php?id_conocimientos=<?php echo $recorrerConocimiento['id_conocimientos'] ?>" class="btn-close botonEliminar botonEliminarConocimiento" aria-label="Close"></a>
                            <span><?php echo $recorrerConocimiento['nombre_conocimiento'] ?></span>

                        </div>

                <?php
                    }
                }

                ?>

            </div>

            <!-- LINK DE PORTAFOLIO -->
            <?php
            if ($recorrerMainAspirante['portafolio'] != "") {
            ?>

                <div class="contenedorPortafolio">

                    <div class="miPortafolio">

                        <span>
                            <!-- MODAL -->
                            <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#examplePortafolio" data-bs-whatever="@mdo">&#x270e</a>
                        </span>

                        <span>Visita mi portafolio:</span>

                        <a href="<?php echo $recorrerMainAspirante['portafolio'] ?>" target=”_blank”> <?php echo limitar_cadena($recorrerMainAspirante['portafolio'], 40, '...') ?> </a>

                        <div style="cursor: pointer;" onclick="eliminarPortafolio(<?php echo $recorrerMainAspirante['portafolio'] ?>)">
                            ❌
                        </div>

                    </div>
                </div>

            <?php
            } else {
            ?>
                <!-- SIN PORTAFOLIO -->
                <div class="contenedorPortafolio">

                    <div class="miPortafolio">
                        <span>Agregar Portafolio...</span>

                        <!-- MODAL -->

                        <!-- se pregunta si el estado esta lleno o no viene vacio, esto hace que si o si ya se halla rellenado los datos faltantes  -->
                        <?php
                        if ($recorrerMainAspirante['estado_trabajo'] != null || $recorrerMainAspirante['estado_trabajo'] != "") {
                        ?>
                            <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#examplePortafolio" data-bs-whatever="@mdo">+</a>
                        <?php
                        }
                        ?>



                    </div>
                </div>

            <?php
            }

            ?>

            <!-- LINK DE CERTIFICADOS -->
            <div class="contenedorPortafolio">

                <!-- mustra el protafolio si es que se relleno los datos basicos -->
                <?php
                if ($recorrerMainAspirante['id_curriculum'] != null) {
                ?>

                    <div class="miPortafolio">

                        <?php
                        $queryBuscarCertificado = mysqli_query($conn, "SELECT otrosLinks FROM curriculum WHERE fk_id_usuEstudiantes = '$id_aspirante' ");
                        $recorrerCertificado = mysqli_fetch_array($queryBuscarCertificado);

                        // entra al if si exite el link del certificado
                        if ($recorrerCertificado['otrosLinks'] != null || $recorrerCertificado['otrosLinks'] != '') {
                        ?>
                            <span>


                                <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#exampleCertificado" data-bs-whatever="@mdo">&#x270e</a>

                                Mis certificados...

                                <a href="<?php echo $recorrerCertificado['otrosLinks'] ?>" target="_blank"><?php echo limitar_cadena($recorrerCertificado['otrosLinks'], 40, '...') ?></a>
                            </span>
                        <?php
                        } else {
                        ?>
                            <span>
                                Sin Certificados

                                <!-- se pregunta si el estado esta lleno o no viene vacio, esto hace que si o si ya se halla rellenado los datos faltantes  -->
                                <?php
                                if ($recorrerMainAspirante['estado_trabajo'] != null || $recorrerMainAspirante['estado_trabajo'] != "") {
                                ?>
                                    <a href="#" class="agregarMas" data-bs-toggle="modal" data-bs-target="#exampleCertificado" data-bs-whatever="@mdo">+</a>
                                <?php
                                }
                                ?>

                            </span> <br>

                            <small>Puedes subir tus cerficados en la nube, tales como: Drive, Dropbox, etc... Y compartir el link por aqui</small>
                        <?php
                        }
                        ?>






                    </div>
                <?php
                }

                ?>

            </div>



        </section>


    </main>


    <!-- MODAL AVATAR -->
    <div class="modal fade" id="exampleAvatar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Foto</h1>
                </div>


                <!-- formulario avatar-->
                <div class="modal-body">

                    <form method="post" id="modalAvatar" class="formulario_agregar_experiencia form" enctype="multipart/form-data">


                        <!-- avatar -->
                        <div class="form-floating mb-3 has-validation">

                            <div class="input-group mb-3">
                                <input type="file" name="fotoModal" class="form-control" id="inputImagenModal" accept=".png,.jpg,.jpeg,.gif, .webp" required>
                            </div>
                        </div>

                        <button type="submit" name="guardarFoto" class="btn guardarModal  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL DATOS PERFIL -->
    <div class="modal fade" id="exampleDatosPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Datos Nuevos</h1>
                </div>


                <!-- formulario avatar-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form needs-validation" enctype="multipart/form-data" novalidate>


                        <!-- DATOS -->
                        <div class="form-floating mb-3 has-validation">

                            <!-- nombre y apellido-->
                            <div class="input-group mb-3 has-validation">
                                <input type="text" name="nombreModal" aria-label="First name" class="form-control" placeholder="Nombres" value="<?php echo $recorrerMainAspirante['nombre'] ?>">
                                <input type="text" name="apellidoModal" aria-label="Last name" class="form-control" placeholder="Apellidos" value="<?php echo $recorrerMainAspirante['apellido'] ?>">
                            </div>


                            <!-- fecha de nacimiento -->
                            <div class="mb-3 has-validation">
                                <label for="fechaModal" style="font-size: 1rem;">Fecha de nacimiento</label>
                                <input type="date" name="fechaModal" class="form-control" id="fechaModal fecha" placeholder="Fecha de nacimiento" value="<?php echo $recorrerMainAspirante['fecha_nacimiento'] ?>">
                            </div>


                            <!-- nombre de usuario -->
                            <div class=" mb-3 has-validation">
                                <label for="nombreUsuarioModal" style="font-size: 1rem;">Nombre de Usuario</label>
                                <input type="text" name="nombreUsuarioModal" class="form-control" id="nombreUsuarioModal" placeholder="Nombre de Usuario" value="<?php echo $recorrerMainAspirante['nombreUsuario'] ?>">
                            </div>

                            <!-- numero de celular -->
                            <div class="mb-3 has-validation">
                                <label for="numeroCelularModal" style="font-size: 1rem;">Numero Celular</label>
                                <input type="number" name="numeroCelularModal" class="form-control" id="numeroCelularModal" placeholder="Numero de Celular" value="<?php echo $recorrerMainAspirante['numero_celular'] ?>">
                            </div>

                            <!-- lugar donde vive -->
                            <div class="mb-3 has-validation">
                                <label for="lugarViveModal" style="font-size: 1rem;">Lugar de residencia</label>
                                <input type="text" name="lugarViveModal" class="form-control" id="lugarViveModal" placeholder="Lugar donde vive" value="<?php echo $recorrerMainAspirante['lugar_donde_vive'] ?>">
                            </div>

                            <!-- especialidad -->
                            <div class=" mb-3 has-validation">
                                <label for="especialidadModal" style="font-size: 1rem;">Especialidad</label>
                                <input type="text" name="especialidadModal" class="form-control" id="especialidadModal" placeholder="Esprecializacion" value="<?php echo $recorrerMainAspirante['especializacion_curriculum'] ?>">
                            </div>

                        </div>

                        <button type="submit" name="guardarDatosPerfil" class="btn guardarModal  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL DATOS ESTADO -->
    <div class="modal fade" id="exampleEstado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Estado</h1>
                </div>


                <!-- formulario avatar-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form needs-validation" enctype="multipart/form-data" novalidate>


                        <!-- DATOS -->
                        <div class="form-floating mb-3 has-validation">

                            <!-- Estado -->
                            <div class="input-group mb-3 has-validation">
                                <select class="form-select" name="estadoModal" aria-label="Default select example" required>

                                    <option selected value="" disabled>Selecciona tu estado*</option>
                                    <?php
                                    $queryEstadoEmpleo = mysqli_query($conn, "SELECT * FROM tipo_estado_trabajo");
                                    while ($recorrerEstadoEmpleo = mysqli_fetch_assoc($queryEstadoEmpleo)) {
                                    ?>
                                        <option value="<?php echo $recorrerEstadoEmpleo['nombre'] ?>"><?php echo $recorrerEstadoEmpleo['nombre'] ?></option>
                                    <?php
                                    }
                                    ?>

                                </select>
                            </div>

                            <!-- Descripcion -->
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Descripcion personal*</label>
                                <textarea class="form-control" name="descripcionPersonalModal" id="exampleFormControlTextarea1" rows="3" required maxlength="500"><?php echo $recorrerMainAspirante['detalle_curriculum'] ?></textarea>
                            </div>


                        </div>

                        <button type="submit" name="guardarEstado" class="btn guardarModal  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL CONOCIMIENTOS Y APTITUDES -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Conocimiento</h1>
                </div>


                <!-- formulario conocimiento -->
                <div class="modal-body">
                    <form method="post" class="formulario_agregar_conocimiento form">

                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label" style="font-size: 1rem;">Nombre Conocimiento:</label>
                            <input type="text" name="nombreConocimiento" class="form-control" id="recipient-name" placeholder="Poner solo una palabra clave, eje: Exel">
                        </div>

                        <button type="submit" name="guardarConocimiento" class="btn guardarModal  btn-primary">Guardar</button>
                    </form>
                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                </div>

            </div>
        </div>

    </div>

    <!-- MODAL PORTAFOLIO EDITAR-->
    <div class="modal fade" id="examplePortafolio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Portafolio</h1>
                </div>


                <!-- formulario Experiencia MODAL-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form">

                        <!-- nombre institucion -->
                        <div class="mb-3">
                            <label for="portafolio" class="col-form-label" style="font-size: 1rem;">URL de tu portafolio:</label>
                            <input type="text" name="UrlPortafolioModal" class="form-control" id="portafolio" required>
                        </div>



                        <button type="submit" name="guardarPortafolioModal" class="btn guardarModal  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                </div>

            </div>
        </div>

    </div>

    <!-- MODAL INSERTAR NUEVO PORTAFOLIO -->
    <div class="modal fade" id="examplePortafolio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Portafolio</h1>
                </div>


                <!-- formulario Experiencia MODAL-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form">

                        <!-- nombre institucion -->
                        <div class="mb-3">
                            <label for="portafolio" class="col-form-label" style="font-size: 1rem;">URL de tu portafolio:</label>
                            <input type="text" name="UrlPortafolioModal" class="form-control" id="portafolio" required>
                        </div>



                        <button type="submit" name="guardarPortafolioModal" class="btn guardarModal  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                </div>

            </div>
        </div>

    </div>

    <!-- MODAL INSERTAR NUEVO CERTIFICADO -->
    <div class="modal fade" id="exampleCertificado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Certificado</h1>
                </div>


                <!-- formulario Experiencia MODAL-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form">

                        <!-- nombre institucion -->
                        <div class="mb-3">
                            <label for="portafolio" class="col-form-label" style="font-size: 1rem;">URL de tu Nube:</label>
                            <input type="text" name="UrlCertificadoModal" class="form-control" id="portafolio" required placeholder="Puedes subir tus certificados a 'Drive' y porner la URL aqui ">
                        </div>



                        <button type="submit" name="guardarCertificadoModal" class="btn guardarModal  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                </div>

            </div>
        </div>

    </div>

    <!-- MODAL NUEVA CONTRASEÑA -->
    <div class="modal fade" id="exampleContraseña" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Contraseña</h1>
                </div>


                <!-- formulario Experiencia MODAL-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form">

                        <!-- nombre institucion -->
                        <div class="mb-3">
                            <label for="portafolio" class="col-form-label" style="font-size: 1rem;">Ingresa la nueva contraseña:</label>
                            <input type="text" name="nuevaContraseñaModal" class="form-control" id="portafolio" required>
                        </div>



                        <button type="submit" name="guardarContraseñaModal" class="btn guardarModal  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                </div>

            </div>
        </div>

    </div>


    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- VALIDAR FORMULARIO VACIOS -->
    <script src="./validarFormulariosVacios.js"></script>



    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>



    <!-- evitar el reenvio de formularios -->
    <script src="../evitarReenvioFormulario.js"></script>


    <!-- confirmacion eliminar -->
    <script src="../ELIMINACIONES/confirmacion.js"></script>

    <!-- alerta personalizada -->
    <script src="./alertaPersonalizada.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        // incializa la animacion
        AOS.init();


        // cuando se haga click en el avatar (para ver la imagen de pefil)
        const verAvar = _ => {

            document.documentElement.scrollTop = 0;
            document.body.style.overflow = 'hidden';

            const imagenPerfil = document.getElementById('imagenPerfil')
            const mostrarAvatar = document.getElementById('contenedorVerAvatar')

            mostrarAvatar.innerHTML = `
                <div class="subContenedorVerAvatar">
                    <span onclick="cerrarAvatar()" class="cerrar">X</span>
                    <img src="${imagenPerfil.src}" alt="">
                </div>
            
            `

        }

        // cerrar el avatar
        const cerrarAvatar = _ => {
            document.body.style.overflow = 'auto';

            const mostrarAvatar = document.getElementById('contenedorVerAvatar')
            mostrarAvatar.innerHTML = ''
        }



        // MODAL IMAGEN AVATAR
        const modalAvatar = document.getElementById('modalAvatar')

        modalAvatar.addEventListener('submit', function(e) {

            // si la foto esta vacia
            if (document.getElementById('inputImagenModal').value == '') {
                alert('Foto Vacia')
                e.preventDefault()
                return
            }

            // obtiene todos los datos de la imagen
            let inputImagenModal = document.getElementById('inputImagenModal').files[0]

            // saca el tamaño de la imagen en kb
            let imagenSize = inputImagenModal.size / 1000

            // 100 kb es lo maximo que se puede ingresar
            if (imagenSize > 100) {

                e.preventDefault()
                alertaPersonalizada('ERROR', 'Imagen muy pesada.', 'error', 'Regresar', 'Ingrese <a target="_blank" href="https://squoosh.app">Aquí</a> para bajar el peso a la imagen')
                return
            }


        })


        // IR IMPRIMIR 
        const irImprimir = id => {

            FD = new FormData();
            FD.append('id', id)

            fetch('queryIrImprimir.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = '../IMPRIMIRPDF/plantillaCv.php'
                    }


                })

        }

        // ir a el detalle de la oferta
        const irOferta = (id_oferta, id_empresa) => {

            FD_ir = new FormData()
            FD_ir.append('id_oferta', id_oferta)



            fetch('../queryIrOferta.php', {
                    method: 'POST',
                    body: FD_ir
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = `../DETALLEOFERTA/detalleOferta.php?id_empresa=${id_empresa}`
                    }

                })
        }
    </script>
</body>

</html>