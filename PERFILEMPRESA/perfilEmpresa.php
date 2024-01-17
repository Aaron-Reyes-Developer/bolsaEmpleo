<?php
session_start();

//si no se encuentra el perfil
if (!isset($_SESSION['id_empresa'])) {
    header('Location: ../LOGIN/login.php');
    die();
}

include('../conexion.php');
include('../funciones.php');


// eliminar la session de el id_oferta para que no halla ningun inconveniente
if (isset($_SESSION['id_oferta'])) {
    unset($_SESSION['id_oferta']);
}


$id_empresa = $_SESSION['id_empresa'];



// notificacion
$queryNotificacion = mysqli_query($conn, "call notificacionEmpresa('$id_empresa')");
$n_r_notificacion = mysqli_num_rows($queryNotificacion);
while (mysqli_next_result($conn)) {;
}


// si se apreta 'marcar como leido en la notificaion
if (isset($_REQUEST['notifiUpdate'])) {

    $actualizarNotificacion = mysqli_query($conn, "UPDATE postula SET estado_noti_empresa = '0' WHERE fk_id_usuario_empresa = '$id_empresa' AND estado_noti_empresa = 1 ");
    if ($actualizarNotificacion > 0) {
        header('Location: ./perfilEmpresa.php');
    }
}


//mostrar todos los datos de la empresa
$queryDatosEmpresa = "SELECT * FROM datos_empresa WHERE fk_id_usuario_empresa = '$id_empresa' ";
$respuestaDatosEmpresa = mysqli_query($conn, $queryDatosEmpresa);

$recorrerDatosEmpresa = mysqli_fetch_array($respuestaDatosEmpresa);



// si por alguna razon no se rellena el ultimo registro de la empresa al momento de registrarse se borrara el usuario y toda existencia de este
if (
    $recorrerDatosEmpresa['imagen_perfil'] == null &&
    $recorrerDatosEmpresa['nombreUsuario'] == null &&
    $recorrerDatosEmpresa['lugarMaps'] == null &&
    $recorrerDatosEmpresa['gerente_general'] == null &&
    $recorrerDatosEmpresa['recursos_humanos'] == null &&
    $recorrerDatosEmpresa['antiguedad_empresa'] == null &&
    $recorrerDatosEmpresa['pagina_web'] == null &&
    $recorrerDatosEmpresa['ofertas_aprobadas'] == null
) {
    $queryEliminarDatosEmpresa = mysqli_query($conn, "DELETE FROM datos_empresa WHERE (fk_id_usuario_empresa = '$id_empresa')");
    while (mysqli_next_result($conn)) {;
    }

    $queryEliminarCorreo = mysqli_query($conn, "DELETE FROM usuario_empresa WHERE (id_usuario_empresa = '$id_empresa')");
    while (mysqli_next_result($conn)) {;
    }

    header("Location: ../LOGIN/login.php");
}



//MOSTRAR LAS OFERTAS QUE TIENE LA EMPRESA
$queryOfertaEmpresa = "SELECT * FROM oferta_trabajo WHERE fk_id_usuario_empresa = '$id_empresa' AND estado_oferta = 1 ORDER BY fecha_oferta DESC LIMIT 5 ";
$resultadoOfertaEmpresa = mysqli_query($conn, $queryOfertaEmpresa);



// MODAL FOTO
if (isset($_POST['guardarFoto'])) {


    $fotoModal = addslashes(file_get_contents($_FILES['fotoModal']['tmp_name']));

    $queryNuevaFoto = "UPDATE datos_empresa SET imagen_perfil = '$fotoModal' WHERE fk_id_usuario_empresa = '$id_empresa' ";

    $respuestaNuevaFoto = mysqli_query($conn, $queryNuevaFoto);

    if (!$respuestaNuevaFoto) {
        die(mysqli_error($conn));
    }

    header('Location: ./perfilEmpresa.php');
}

// MODAL DATOS PERFIL
if (isset($_POST['guardarDatosPerfil'])) {

    if (
        ($_POST['nombreEmpresaModal'] == "") ||
        ($_POST['nombreUsuarioModal'] == "") ||
        ($_POST['correroContactoModal'] == "") ||
        ($_POST['lugarModal'] == "")
    ) {
        echo "<script> alert('Rellena todos los datos') </script>";
        echo "<script> window.location.href = './perfilEmpresa.php' </script>";
        die();
    }

    $nombreEmpresaModal = htmlspecialchars($_POST['nombreEmpresaModal']);
    $nombreUsuarioModal = htmlspecialchars($_POST['nombreUsuarioModal']);
    $correroContactoModal = htmlspecialchars($_POST['correroContactoModal']);
    $lugarModal = htmlspecialchars($_POST['lugarModal']);


    $queryActualizarDatos = "UPDATE datos_empresa SET nombre = '$nombreEmpresaModal', correo = '$correroContactoModal', lugar = '$lugarModal', nombreUsuario = '$nombreUsuarioModal' WHERE fk_id_usuario_empresa = '$id_empresa' ";
    $respuestaActualizarDatos = mysqli_query($conn, $queryActualizarDatos);

    if (!$respuestaActualizarDatos) {
        die(mysqli_error($conn));
    }

    header('Location: ./perfilEmpresa.php');
}

// Modal detelle de la empresa
if (isset($_POST['guardarDetalleEmpresaModal'])) {

    // verficar que el dato no venga vacio
    if ((!isset($_POST['detalleEmpresaModal'])) || ($_POST['detalleEmpresaModal'] == "")) {
        echo "<script> alert('Dato Vacio') </script>";
        echo "<script> window.location.href = './perfilEmpresa.php' </script>";
    }

    // obtener datos
    $detalleEmpresaModal = htmlspecialchars($_POST['detalleEmpresaModal']);

    $queryEditarDetalle = "UPDATE `datos_empresa` SET `detalle_empresa` = '$detalleEmpresaModal' WHERE `datos_empresa`.`fk_id_usuario_empresa` =  '$id_empresa'";
    $respuestaEditarDetalle = mysqli_query($conn, $queryEditarDetalle);

    if ($respuestaEditarDetalle) {
        header('Location: ./perfilEmpresa.php');
    } else {
        echo mysqli_error($conn);
    }
}

// Modal Personal empresa
if (isset($_POST['guardarPersonalEmpresaModal'])) {


    // verficar que el dato no venga vacio
    if (
        (!isset($_POST['gerenteGeneralModal'])) ||
        ($_POST['gerenteGeneralModal'] == "") ||
        (!isset($_POST['recursoHumanolModal'])) ||
        ($_POST['recursoHumanolModal'] == "")
    ) {

        echo "<script> alert('Datos Vacio') </script>";
        echo "<script> window.location.href = './perfilEmpresa.php' </script>";
    }

    // obtener datos
    $gerenteGeneral = htmlspecialchars($_POST['gerenteGeneralModal']);
    $recursoHumano = htmlspecialchars($_POST['recursoHumanolModal']);

    $queryEditarPersonal = "UPDATE datos_empresa SET gerente_general = '$gerenteGeneral', recursos_humanos = '$recursoHumano' WHERE fk_id_usuario_empresa = '$id_empresa' ";
    $respuestaEditarPersonal = mysqli_query($conn, $queryEditarPersonal);
    if ($respuestaEditarPersonal) {
        header('Location: ./perfilEmpresa.php');
    }
}

// Modal Antiguedad
if (isset($_POST['guardarAntiguedad'])) {


    // verficar que el dato no venga vacio
    if ($_POST['antiguedadModal'] == "") {

        echo "<script> alert('Datos Vacio') </script>";
        echo "<script> window.location.href = './perfilEmpresa.php' </script>";
    }

    // obtener datos
    $antiguedadModal = htmlspecialchars($_POST['antiguedadModal']);


    $queryEditarAntiguedad = "UPDATE datos_empresa SET antiguedad_empresa = '$antiguedadModal' WHERE fk_id_usuario_empresa = '$id_empresa' ";
    $respuestaEditarAntiguedad = mysqli_query($conn, $queryEditarAntiguedad);
    if ($respuestaEditarAntiguedad) {
        header('Location: ./perfilEmpresa.php');
    } else {
        die(mysqli_error($conn));
    }
}

// Modal Servicio
if (isset($_POST['guardarServicio'])) {


    // verficar que el dato no venga vacio
    if ($_POST['servicioModal'] == "") {

        echo "<script> alert('Datos Vacio') </script>";
        echo "<script> window.location.href = './perfilEmpresa.php' </script>";
    }

    // obtener datos
    $servicioModal = htmlspecialchars($_POST['servicioModal']);


    $queryEditarservicioModal = "UPDATE datos_empresa SET servicios_ofrecer = '$servicioModal' WHERE fk_id_usuario_empresa = '$id_empresa' ";
    $respuestaEditarservicioModal = mysqli_query($conn, $queryEditarservicioModal);
    if ($respuestaEditarservicioModal) {
        header('Location: ./perfilEmpresa.php');
    } else {
        die(mysqli_error($conn));
    }
}

// Modal Maps
if (isset($_POST['guardarMaps'])) {


    // verficar que el dato no venga vacio
    if ($_POST['mapsModal'] == "") {

        echo "<script> alert('Datos Vacio') </script>";
        echo "<script> window.location.href = './perfilEmpresa.php' </script>";
    }

    // obtener datos
    $mapsModal = $_POST['mapsModal'];


    $queryEditarMapsModal = "UPDATE datos_empresa SET lugarMaps = '$mapsModal' WHERE fk_id_usuario_empresa = '$id_empresa' ";
    $respuestaEditarMapsModalModal = mysqli_query($conn, $queryEditarMapsModal);
    if ($respuestaEditarMapsModalModal) {
        header('Location: ./perfilEmpresa.php');
    } else {
        die(mysqli_error($conn));
    }
}

// Modal Link
if (isset($_POST['guardarLink'])) {


    // verficar que el dato no venga vacio
    if ($_POST['linkModal'] == "") {

        echo "<script> alert('Datos Vacio') </script>";
        echo "<script> window.location.href = './perfilEmpresa.php' </script>";
    }

    // obtener datos
    $linkModal = htmlspecialchars($_POST['linkModal']);


    $queryEditarLink = "UPDATE datos_empresa SET pagina_web = '$linkModal' WHERE fk_id_usuario_empresa = '$id_empresa' ";
    $respuestaEditarLink = mysqli_query($conn, $queryEditarLink);
    if ($respuestaEditarLink) {
        header('Location: ./perfilEmpresa.php');
    } else {
        die(mysqli_error($conn));
    }
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
    $queryActualizarContra = mysqli_query($conn, "UPDATE usuario_empresa SET contra = '$nuevaContra' WHERE id_usuario_empresa = '$id_empresa' ");


    // borramos la contraseña temporal
    $queryActualizarContraTemporal = mysqli_query($conn, "UPDATE usuario_empresa SET contra_temporal = NULL WHERE id_usuario_empresa = '$id_empresa'  ");


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


    <!-- ALERTA PERSONALIZAD -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">



    <link rel="stylesheet" href="estiloPerfilEmpresa.css">
    <title>Empresa</title>
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

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./INICIOEMPRESA/inicioEmpresa.php"><img src="../imagenes/Iconos/casa.svg" alt="Inicio" title="Inicio"></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../COMENTARIOS/comentarios.php"><img src="../imagenes/Iconos/comentarios.png" alt="Comentarios" title="Comentarios"></a>
                        </li>

                        <!-- icono campana -->
                        <li class="nav-item iconoLisa iconoCampana">

                            <?php

                            // si existe notificacion
                            if ($n_r_notificacion >= 1) {


                            ?>
                                <div class="dropdown">

                                    <!-- icono campana con la notificacion -->
                                    <a style="text-decoration: none; position: relative;  cursor: pointer;" class="iconoEnlace" data-bs-toggle="dropdown" aria-expanded="false">

                                        <img src="../imagenes/Iconos/campana.svg" alt="">

                                        <div class="bg-danger rounded text-light numero_icono" style="position: absolute;top: -15px;right: -10px; min-width: 20px; display: flex; justify-content: center; align-items: center;">
                                            <?php echo $n_r_notificacion ?>
                                        </div>
                                    </a>

                                    <ul class="dropdown-menu">

                                        <?php

                                        while ($recorrerNoti = mysqli_fetch_array($queryNotificacion)) {

                                        ?>
                                            <li>
                                                <a onclick="irOferta(<?php echo $recorrerNoti['id_oferta_trabajo'] ?>)" class="dropdown-item notificacionTexto">
                                                    <?php echo "Aspirantes en: " . "<b>" . $recorrerNoti['puesto'] . "</b>" ?>
                                                </a>
                                            </li>

                                        <?php
                                        }
                                        ?>

                                        <hr>
                                        <li><a class="dropdown-item marcarComoLeido" href="?notifiUpdate=ok">Marcar como leido</a></li>
                                        <li><a class="dropdown-item" href="./NOTIFICACIONES/notificaciones.php">Ver todas las notifi...</a></li>
                                    </ul>
                                </div>
                            <?php


                                // si no existe notificacion
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
                                        <li><a class="dropdown-item" href="./NOTIFICACIONES/notificaciones.php">Ver todas la notificaciones...</a></li>
                                    </ul>
                                </div>



                            <?php
                            }


                            ?>

                        </li>



                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../cerrarSesion.php"><img src="../imagenes/Iconos/salir.svg" alt="Cerrar Sesion" title="Cerrar Sesion"></a>
                        </li>

                        <li class="nav-item lista-avatar-nav" style="width: 50px; height: 50px;">
                            <a class="nav-link enlace-avatar" aria-current="page" href="#" style="padding: 0;"><img style="width: 100%; height: 100%; object-fit: cover;" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerDatosEmpresa['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>



    <main class="main">

        <!-- PERFIL -->
        <section class="seccionPerfil">

            <!-- PORTADA Y AVATAR -->
            <div class="contenedorPortada">

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


                <div class="contendorFotoPerfil" style="cursor: pointer;">

                    <img onclick="verAvar()" id="imagenPerfil" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerDatosEmpresa['imagen_perfil']) ?>" alt="">


                    <a href="#" class="editarAvatar" class="" data-bs-toggle="modal" data-bs-target="#exampleAvatar" data-bs-whatever="@mdo">&#x270e</a>
                </div>
            </div>

            <!-- DATOS -->
            <div class="datosPerfil mt-5 mb-4">
                <div class="informacionPerfil">
                    <h3><?php echo $recorrerDatosEmpresa['nombreUsuario'] ?> <a href="#" class="editarPerfil" class="" data-bs-toggle="modal" data-bs-target="#exampleDatosPerfil" data-bs-whatever="@mdo">&#x270e</a></h3>
                    <span>Contacto: <?php echo $recorrerDatosEmpresa['correo'] ?></span> <br>
                    <span><?php echo $recorrerDatosEmpresa['lugar'] ?></span>
                </div>
            </div>

            <hr class="hr">

            <!-- PUESTOS OFERTADOS -->
            <div class="puestos_ofertados">

                <h3>
                    Puestos Ofertados
                    <a href="./INGRESAROFERTA/ingresarOferta.php">+</a><br>
                    <a href="./MOSTRARTODASOFERTA/mostrarTodasOferta.php" class="mostrarTodasLasOfertas">Mostras todas las ofertas</a>
                </h3>

                <!-- OFERTAS DE LA EMPRESA -->
                <div class="contenedorOfertas">
                    <?php

                    while ($recorrerOfertaEmpresa = mysqli_fetch_array($resultadoOfertaEmpresa)) {
                    ?>
                        <div class="ofertaCarta">
                            <h4><?php echo $recorrerOfertaEmpresa['puesto'] ?></h4>
                            <span><?php echo limitar_cadena($recorrerOfertaEmpresa['detalle'], 63, '...') ?></span><br><br>
                            <a onclick="irOferta(<?php echo $recorrerOfertaEmpresa['id_oferta_trabajo'] ?>)" class="verDetalles" style="color: blue; cursor: pointer;">Ver detalles...</a>
                            <a href="./INGRESAROFERTA/ingresarOferta.php?editar=ok&id_oferta=<?php echo $recorrerOfertaEmpresa['id_oferta_trabajo'] ?>" class="editar">Editar...</a>
                        </div>

                    <?php
                    }

                    ?>

                </div>
            </div>

        </section>

        <!-- ACERCA DE -->
        <section class="seccionAcerdaDe">

            <div class="acerdaDe">

                <h3>
                    Acerda de
                    <a href="#" class="editarDescripcion" data-bs-toggle="modal" data-bs-target="#editarAcercaDe" data-bs-whatever="@mdo">&#x270e</a>

                </h3>

                <hr>

                <p class="parrafoAcerdaDe"><?php echo limitar_cadena($recorrerDatosEmpresa['detalle_empresa'], 244, '...') ?></p>

            </div>

            <div class="personalEmpresa">
                <h3>
                    Personal Empresa
                    <a href="#" class="editarDescripcion" data-bs-toggle="modal" data-bs-target="#editarPersonalEmpresa" data-bs-whatever="@mdo">&#x270e</a>
                </h3>
                <hr>

                <ul>
                    <li> <b>Gerente General: </b> <?php echo $recorrerDatosEmpresa['gerente_general'] ?></li>
                    <li> <b>Recursos Humanos: </b> <?php echo $recorrerDatosEmpresa['recursos_humanos'] ?></li>
                </ul>
            </div>

            <div class="personalEmpresa">
                <h3>
                    Oferta Aprobadas
                </h3>
                <hr>

                <a href="./MOSTRARTODASOFERTA/mostrarTodasOferta.php?puestosAprobados=ok" class="totalPuestosAprobados"><?php echo $recorrerDatosEmpresa['ofertas_aprobadas'] ?></a>
            </div>

        </section>

        <!-- INFORMACION -->
        <section class="informacionEmpresa">

            <h3>Informacion de la Empresa</h3>


            <div class="contenedorInformacionEmpresa">

                <!-- AÑOS -->
                <div class="años">

                    <div class="contenedorImagenReloj">
                        <img src="https://icones.pro/wp-content/uploads/2021/03/symbole-de-l-horloge-verte.png" alt="">
                    </div>

                    <div class="textoAño">
                        <h3><?php echo $recorrerDatosEmpresa['antiguedad_empresa'] ?> Años</h3>
                        <span>antigüedad aproximada</span>
                    </div>

                    <a href="#" class="editarAntiguedad" data-bs-toggle="modal" data-bs-target="#exampleAntiguedad" data-bs-whatever="@mdo">&#x270e</a>
                </div>

                <!-- SERVICIOS -->
                <div class="texto">
                    <p class="textoServicio"><b>Servicios que Ofrecemos</b></p>
                    <p class="parrafoServicio"><?php echo limitar_cadena($recorrerDatosEmpresa['servicios_ofrecer'], 264, '...') ?></p>

                    <a href="#" class="editarAntiguedad" data-bs-toggle="modal" data-bs-target="#exampleServicio" data-bs-whatever="@mdo">&#x270e</a>
                </div>

                <!-- MAPS -->
                <div class="maps">
                    <?php echo $recorrerDatosEmpresa['lugarMaps'] ?>
                    <a href="#" class="editarAntiguedad" data-bs-toggle="modal" data-bs-target="#exampleMaps" data-bs-whatever="@mdo">&#x270e</a>
                </div>

            </div>

        </section>

        <!-- LINK -->
        <footer class="linkEmpresa">
            <div class="subcontenedorLink">
                <a href="#" class="editarLink" class="" data-bs-toggle="modal" data-bs-target="#exampleLink" data-bs-whatever="@mdo">&#x270e</a>
                <span class="visitraMiPagina">Visita mi Pagina: </span>
            </div>
            <a class="linkDirecto" target="_blank" href="<?php echo $recorrerDatosEmpresa['pagina_web'] ?>"> <?php echo Limitar_cadena($recorrerDatosEmpresa['pagina_web'], 22, '...') ?> </a>
        </footer>
    </main>


    <!-- MODAL AVATAR -->
    <div class="modal fade" id="exampleAvatar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Foto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- formulario avatar-->
                <div class="modal-body">

                    <form id="formularioModalAvatar" method="post" class="formulario_agregar_experiencia form" enctype="multipart/form-data">


                        <!-- avatar -->
                        <div class="form-floating mb-3 has-validation">

                            <div class="input-group mb-3">
                                <input type="file" name="fotoModal" class="form-control" id="inputImagenModal" accept=".png,.jpg,.jpeg,.gif" required>
                            </div>
                        </div>

                        <button type="submit" name="guardarFoto" class="btn botonModalGuardar  btn-primary">Guardar</button>

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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Datos Nuevos Empresa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- formulario Datos-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form needs-validation" enctype="multipart/form-data" novalidate>


                        <!-- DATOS -->
                        <div class="form-floating mb-3 has-validation">

                            <!-- nombre de la empresa -->
                            <div class="input-group mb-3 has-validation">
                                <input type="text" name="nombreEmpresaModal" aria-label="First name" class="form-control" placeholder="Nombre de la Empresa" value="<?php echo $recorrerDatosEmpresa['nombre'] ?>" required>
                            </div>

                            <!-- nombre de usuario -->
                            <div class="input-group mb-3 has-validation">
                                <input type="text" name="nombreUsuarioModal" aria-label="First name" class="form-control" placeholder="Nombre de Usuario" value="<?php echo $recorrerDatosEmpresa['nombreUsuario'] ?>" required>
                            </div>

                            <!-- correo contacto -->
                            <div class="mb-3 has-validation">
                                <input type="email" name="correroContactoModal" class="form-control" id="inputGroupFile01 fecha" placeholder="Correo de contacto" value="<?php echo $recorrerDatosEmpresa['correo'] ?>" required>
                            </div>

                            <!-- Lugar -->
                            <div class="input-group mb-3 has-validation">
                                <input type="text" name="lugarModal" class="form-control" id="inputGroupFile01" placeholder="Lugar: Jipijapa-Manabi-Ecuador" value="<?php echo $recorrerDatosEmpresa['lugar'] ?>" required>
                            </div>



                        </div>

                        <button type="submit" name="guardarDatosPerfil" class="btn botonModalGuardar  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL EDITAR ACERDA DE-->
    <div class="modal fade" id="editarAcercaDe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Detalle de la Empresa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- formulario-->
                <div class="modal-body">

                    <form method="post" class="formulario form">

                        <!-- detalle de la empresa -->
                        <div class="mb-3">
                            <label for="portafolio" class="col-form-label">Detalle de la empresa:</label>
                            <textarea class="form-control" name="detalleEmpresaModal" id="message-text" required><?php echo $recorrerDatosEmpresa['detalle_empresa'] ?></textarea>
                        </div>

                        <button type="submit" name="guardarDetalleEmpresaModal" class="btn botonModalGuardar  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                </div>

            </div>
        </div>

    </div>

    <!-- MODAL EDITAR PERSONAL EMPRESA -->
    <div class="modal fade" id="editarPersonalEmpresa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Personal de la Empresa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- formulario-->
                <div class="modal-body">

                    <form method="post" class="formulario form">

                        <!-- Personal -->
                        <div class="form-group mb-3">
                            <label for="recipient-name" class="col-form-label">Gerente General:</label>
                            <input type="text" name="gerenteGeneralModal" class="form-control" id="recipient-name" placeholder="Ing. Aaron Josue Reyes Carvajal" value="<?php echo $recorrerDatosEmpresa['gerente_general'] ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="recipient-names" class="col-form-label">Recursos Humano:</label>
                            <input type="text" name="recursoHumanolModal" class="form-control" id="recipient-names" placeholder="Ing. Luis Alberto Menendez Salazar" value="<?php echo $recorrerDatosEmpresa['recursos_humanos'] ?>">
                        </div>

                        <button type="submit" name="guardarPersonalEmpresaModal" class="btn botonModalGuardar  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                </div>

            </div>
        </div>

    </div>

    <!-- MODAL ANTIGUEDAD -->
    <div class="modal fade" id="exampleAntiguedad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Antiguedad</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- formulario Antiguedad-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form needs-validation" novalidate>


                        <!-- DATOS -->
                        <div class="form-floating mb-3 has-validation">

                            <!-- Antiguedad -->
                            <div class="input-group mb-3 has-validation">
                                <input type="number" name="antiguedadModal" aria-label="First name" class="form-control" placeholder="Antiguedad de la Empresa" value="<?php echo $recorrerDatosEmpresa['antiguedad_empresa'] ?>" required>
                            </div>

                        </div>

                        <button type="submit" name="guardarAntiguedad" class="btn botonModalGuardar  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL SERVICIO -->
    <div class="modal fade" id="exampleServicio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Servicio</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- formulario Servicio-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form needs-validation" novalidate>


                        <!-- DATOS -->
                        <div class="form-floating mb-3 has-validation">

                            <!-- Antiguedad -->
                            <div class="mb-3">
                                <textarea class="form-control" name="servicioModal" id="exampleFormControlTextarea1" rows="13" placeholder="Servicios que ofrece la Empresa" required><?php echo $recorrerDatosEmpresa['servicios_ofrecer'] ?></textarea>
                            </div>

                        </div>

                        <button type="submit" name="guardarServicio" class="btn botonModalGuardar  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL MAPS -->
    <div class="modal fade" id="exampleMaps" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Lugar <a target="_blank" href="https://www.google.com/maps/">Maps</a></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- formulario Maps-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form needs-validation" novalidate>


                        <!-- DATOS -->
                        <div class="form-floating mb-3 has-validation">

                            <!-- Maps -->
                            <div class="mb-3">
                                <textarea class="form-control" name="mapsModal" id="exampleFormControlTextarea1" rows="7" placeholder="Introduce el <iframe> de google maps" required></textarea>
                            </div>

                        </div>

                        <button type="submit" name="guardarMaps" class="btn botonModalGuardar  btn-primary">Guardar</button>

                    </form>

                </div>


                <!-- boton cerra modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL LINK -->
    <div class="modal fade" id="exampleLink" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <!-- titulo -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Link Pagina Web</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- formulario Maps-->
                <div class="modal-body">

                    <form method="post" class="formulario_agregar_experiencia form needs-validation" novalidate>


                        <!-- DATOS -->
                        <div class="form-floating mb-3 has-validation">

                            <!-- Maps -->
                            <div class="mb-3">
                                <textarea class="form-control" name="linkModal" id="exampleFormControlTextarea1" rows="2" placeholder="Introduce el link Web de la Empresa" required><?php echo $recorrerDatosEmpresa['pagina_web'] ?></textarea>
                            </div>

                        </div>

                        <button type="submit" name="guardarLink" class="btn botonModalGuardar  btn-primary">Guardar</button>

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
                            <label for="portafolio" class="col-form-label">Ingresa la nueva contraseña:</label>
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


    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <!-- alerta personalizada -->
    <script src="./alertaPersonalizada.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>



    <!-- VALIDAR FORMULARIOS -->
    <script src="./validarFormulario.js"></script>
    <script src="../evitarReenvioFormulario.js"></script>


    <script>
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
        const modalAvatar = document.getElementById('formularioModalAvatar')

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


        // ir a el detalle de la oferta
        const irOferta = id => {

            FD_ir = new FormData()
            FD_ir.append('id_oferta', id)



            fetch('../queryIrOferta.php', {
                    method: 'POST',
                    body: FD_ir
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = '../VEROFERTACONASPIRANTES/verOfertasConAspirantes.php'
                    }

                })
        }



        //
    </script>
</body>

</html>