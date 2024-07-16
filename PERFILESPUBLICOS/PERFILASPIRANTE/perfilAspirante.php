<?php

include("../../conexion.php");
include('../../funciones.php');


session_start();
// error_reporting(0);

if (!isset($_SESSION['id_empresa']) || $_SESSION['id_empresa'] == "") {
    header("Location: ../../LOGIN/login.php");
    die();
}


$id_empresa = $_SESSION['id_empresa'];
$id_aspirante = $_SESSION['id_aspirante'];


if (isset($_SESSION['id_oferta'])) {

    $id_oferta = $_SESSION['id_oferta'];


    // consulta para ver si las plazas son mayor a 0
    $queryPlazas = mysqli_query($conn, "SELECT plaza FROM oferta_trabajo WHERE id_oferta_trabajo = $id_oferta");
    $rowPlazas = mysqli_fetch_assoc($queryPlazas);
    $plazas = $rowPlazas['plaza'];


    // actualizar la oferta (desactivarla) si las plazas son 0
    if ($plazas <= 0) {
        $queryDesactivarOferta = mysqli_query($conn, "UPDATE oferta_trabajo SET estado_oferta = '0' WHERE id_oferta_trabajo = $id_oferta");
    }



    //query para consultar el nombre de la oferta (para mandarlo por correo)
    $queryNombreOferta = mysqli_query($conn, "SELECT id_oferta_trabajo ,puesto FROM oferta_trabajo WHERE id_oferta_trabajo = $id_oferta");
    $recorrerOferta = mysqli_fetch_array($queryNombreOferta);
    while (mysqli_next_result($conn)) {;
    }

    $puesto = $recorrerOferta['puesto'];
}


//sacar la foto de la empresa para el header
$queryFotoHeaderEmrpresa = mysqli_query($conn, "SELECT usuEm.id_usuario_empresa, dt.nombre ,dt.imagen_perfil 
FROM usuario_empresa as usuEm 
LEFT JOIN datos_empresa as dt
ON usuEm.id_usuario_empresa = dt.fk_id_usuario_empresa
WHERE usuEm.id_usuario_empresa = '$id_empresa' ");

$recorrerFotoEmpresa = mysqli_fetch_array($queryFotoHeaderEmrpresa);
while (mysqli_next_result($conn)) {;
}

$empresa = $recorrerFotoEmpresa['nombre'];


//query para consultar todos los datos del aspirante (curriculum, experiencia, educacion, idioma, aptitudes, portafolio)c
$queryDatosEstudiantes = "call datosMainEstudiante('$id_aspirante')";
$resultadoDatosEstudiantes = mysqli_query($conn, $queryDatosEstudiantes);



// SI NO SE ENCONTRO EL ASPIRANTE SE MANDA UN 404
if (mysqli_num_rows($resultadoDatosEstudiantes) <= 0) {
    include('./error404.html');
    die();
}

$recorrerDatosEstudiantes = mysqli_fetch_array($resultadoDatosEstudiantes);
while (mysqli_next_result($conn)) {;
}

$correoAspirante = $recorrerDatosEstudiantes['correo'];



// aprobar aspirante
if (isset($_POST['aprobar'])) {

    $fecha_actual = date("Y-m-d");

    // aprobar
    $queryAprobar = "UPDATE postula SET aprobado = '1',estado_noti = '1', fecha_aprobado = '$fecha_actual' WHERE fk_id_usuEstudiantes = '$id_aspirante' AND fk_id_oferta_trabajo = '$id_oferta' ";
    $respuestaAprobar = mysqli_query($conn, $queryAprobar);
    while (mysqli_next_result($conn)) {;
    }






    // mandar un correo avisandole que le aprobaron la oferta
    $to = $correoAspirante;
    $subject = '¡Oferta Aprobada!';
    $message = "La empresa " . $empresa . " aprobo  tu solicitud en la oferta: " . "' " . $puesto . " '" . "\r\nIngresa a la Bolsa de Empleo para saber los detalle de la oferta" . "\r\nEste al pendiente de su correo o numero celular.";
    $headers = 'From: soporte@trabajounesum.com' . "\r\n" .
        'Reply-To: soporte@trabajounesum.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();


    // query para decrementar la plaza de la oferta de trabajo
    $queryDecrementarPlaza = mysqli_query($conn, "UPDATE oferta_trabajo SET plaza = plaza - 1 WHERE id_oferta_trabajo = $id_oferta");
    while (mysqli_next_result($conn)) {;
    }


    // actualizar las foertas aprobadas en el perfil de la empresa
    $respuestaActualizarOfertaAprobado = mysqli_query($conn, "UPDATE datos_empresa SET ofertas_aprobadas = ofertas_aprobadas + 1 WHERE fk_id_usuario_empresa = '$id_empresa'");
    while (mysqli_next_result($conn)) {;
    }


    // si se apruba correctamente mandamos un correo
    if ($respuestaAprobar &&  mail($to, $subject, $message, $headers)) {
?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalAprobado.js"></script>

        </body>


<?php
    } else {
        die(mysqli_error($conn));
    }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../imagenes/iconos/iconoAdmin/iconoPaginas.gif">

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


    <link rel="stylesheet" href="../../PERFILASPIRANTE/estiloPerfilAspirante.css">
    <link rel="stylesheet" href="./estiloAspirante.css">
    <title>Aspirante</title>
</head>

<body>

    <!-- para mostrar el avatar en toda la pantall -->
    <div class="contenedorVerAvatar" id="contenedorVerAvatar"></div>


    <header class="">

        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../../index.html">
                    <img src="../../imagenes/logoUnesum.png" alt="">
                </a> -->

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav">

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../PERFILEMPRESA/INICIOEMPRESA/inicioEmpresa.php"><img src="../../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../PERFILEMPRESA/INICIOEMPRESA/postulantes.php"><img src="../../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="#"><img src="../../imagenes/Iconos/campana.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../../cerrarSesion.php"><img src="../../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav" style="width: 50px; height: 50px;">
                            <a class="nav-link enlace-avatar" aria-current="page" href="../../PERFILEMPRESA/perfilEmpresa.php" style="padding: 0;"><img style="width: 100%; height: 100%; object-fit: cover;" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerFotoEmpresa['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="main">

        <!-- seccion perfil -->
        <section class="seccionPerfil">

            <div class="contenedorDatosPerfil">

                <!-- AVATAR -->
                <div class="protada">
                    <img src="../../imagenes/portadaDefectoPerfil.jpg" alt="">

                    <div class="contenedor-avatar">
                        <img onclick="verAvar()" id="imagenPerfil" class="imagenPerfil" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerDatosEstudiantes['imagen_perfil']) ?>" alt="">
                    </div>
                </div>


                <!-- DATOS -->
                <div class="datosPerfil mt-5 mb-4">
                    <div class="informacionPerfil">
                        <h3><?php echo $recorrerDatosEstudiantes['nombre'], " ", $recorrerDatosEstudiantes['apellido'] ?></h3>
                        <span><?php echo $recorrerDatosEstudiantes['tituloGraduado'] ?></span> <br>
                        <span>Especialidad: <?php echo $recorrerDatosEstudiantes['especializacion_curriculum'] ?></span> <br>
                        <span>Edad: <?php echo calcularEdad($recorrerDatosEstudiantes['fecha_nacimiento'])  ?></span> <br>
                        <span>@<?php echo $recorrerDatosEstudiantes['nombreUsuario'] ?></span> <br>
                        <span>Contacto: <?php echo $recorrerDatosEstudiantes['correo'] ?></span> <br>
                        <span>Numero Celular: <a target="_blank" href="https://wa.me/+593<?php echo $recorrerDatosEstudiantes['numero_celular'] ?>"><?php echo $recorrerDatosEstudiantes['numero_celular'] ?></a></span> <br>
                        <span>Numero de Cedula: <?php echo $recorrerDatosEstudiantes['cedula'] ?></span> <br>
                        <span><?php echo $recorrerDatosEstudiantes['lugar_donde_vive'] ?></span>
                    </div>
                </div>

                <!-- ESTADO -->
                <div class="estadoPerfil">
                    <h4>
                        <?php
                        if ($recorrerDatosEstudiantes['estado_trabajo'] != null || $recorrerDatosEstudiantes['estado_trabajo'] != "") {
                            echo $recorrerDatosEstudiantes['estado_trabajo'];
                        } else {
                            echo '<i>Sin informacion de estado</i>';
                        }

                        ?>
                    </h4>


                    <span>

                        <?php

                        if ($recorrerDatosEstudiantes['detalle_curriculum'] != null || $recorrerDatosEstudiantes['detalle_curriculum'] != "") {
                            echo $recorrerDatosEstudiantes['detalle_curriculum'];
                        } else {
                            echo 'Sin informacion de el aspirante';
                        }

                        ?>

                    </span>


                </div>

            </div>


            <!-- GUARDAR / IMPRIMIR / APROBAR -->
            <div class="contenedorBotones">

                <!-- /////////////////////////////////////////////////      GUARDAR         /////////////////////////////////////////////////-->
                <?php
                if (isset($id_oferta)) {

                ?>
                    <div class="contenedorGuardar">

                        <?php
                        // query para saber si el aspirante esta guardado
                        $queryApiranteGuardado = mysqli_query($conn, "SELECT id_estudiante_guardado FROM estudiantes_guardados WHERE fk_id_usuario_estudiante = $id_aspirante AND fk_id_oferta_trabajo  = $id_oferta");

                        // entra si el aspirante esta guardaro
                        if (mysqli_num_rows($queryApiranteGuardado) >= 1) {
                        ?>

                            <img id="imagenDesguardar" onclick="desguardarAspirante(<?php echo $id_aspirante ?>, <?php echo $id_oferta ?>)" src="../../imagenes/Iconos/desguadar.webp" width="40px" alt="" title="Eliminar Aspirante">

                        <?php
                        } else {
                        ?>
                            <img id="imagenGuardar" onclick="guardarAspirante(<?php echo $id_aspirante ?>, <?php echo $id_oferta ?>)" src="../../imagenes/Iconos/guardar.webp" width="40px" alt="" title="Guardar Aspirante">

                        <?php
                        }
                        ?>

                    </div>

                <?php
                }
                ?>


                <!-- /////////////////////////////////////////////////      APROBAR         /////////////////////////////////////////////////-->
                <?php

                // si existe el id de la oferta (significa que entro desde una oferta)
                if (isset($id_oferta)) {

                    //consulta para saber si ya se aprobo al aspirante
                    $respuestaYaAprobo = mysqli_query($conn, "SELECT * FROM postula WHERE fk_id_oferta_trabajo = '$id_oferta' and fk_id_usuEstudiantes = '$id_aspirante' and aprobado >= 1 ");

                    // entra si no hay plazas y el aspirante no ha sido aprobado
                    if ($plazas <= 0 && mysqli_num_rows($respuestaYaAprobo) < 1) {

                        // no hay plazas de trabajo
                        echo "<div class='alert alert-warning' role='alert'>No existen más Plazas de trabajo</div>";

                        //
                    } else {


                        // si no esta aprobado se muestra el boton para aprobar
                        if (mysqli_num_rows($respuestaYaAprobo) < 1) {


                ?>
                            <form action="" method="post" class="aprobar">
                                <input type="submit" name="aprobar" value="Aprobar" class="inputAprobar btn btn-primary">
                            </form>
                <?php

                        } else {


                            $rowPostula = mysqli_fetch_assoc($respuestaYaAprobo);
                            $id_postula = $rowPostula['id_postula'];

                            // boton de desaprobar
                            echo "<div onclick='desaprobar($id_postula, $id_oferta, `$puesto`, `$empresa`, `$correoAspirante` )' class='contenedorDesaprobado'>

                                    Desaprobar

                                    <div class='contenedorHover'>
                                        Se le enviara un <br> 
                                        correo al aspirante <br> 
                                        notificándole que ha sido desaprobado
                                    </div>

                                </div>";
                        }
                    }
                }

                ?>


                <!-- /////////////////////////////////////////////////      IMPRIMIR         /////////////////////////////////////////////////-->
                <div class="aprobar imprimir">
                    <a href="../../IMPRIMIRPDF/plantillaCv.php?id_aspirante=<?php echo $id_aspirante ?>" Target="_blank"><img src="../../imagenes/Iconos/imprimir.png" alt="" title="Imprimir Cv"></a>
                </div>


            </div>


        </section>

        <!-- experiencia -->
        <section class="seccionExperiencia">

            <h4> Experiencia </h4>


            <?php

            // extraemos el id_curriculum para usarlo en la consulta de la experiencia
            $id_curriculum = $recorrerDatosEstudiantes['id_curriculum'];

            // mostramos todas las experiencias que tiene el aspirante
            $queryExperiencia = "SELECT * FROM experiencia WHERE fk_id_curriculum = '$id_curriculum' ";
            $resultadoExperiencia = mysqli_query($conn, $queryExperiencia);


            if (mysqli_num_rows($resultadoExperiencia) == 0) {
            ?>
                <div class="alert alert-warning" role="alert">
                    ¡Sin experiencia de empleo!
                </div>
                <?php


            } else {


                while ($mostrarDatosExperiencia = mysqli_fetch_array($resultadoExperiencia)) {

                ?>
                    <!-- carta experiencia -->
                    <div class="contendor-experiencia">

                        <div class="contenedor-imagen-experiencia">
                            <img src="../../imagenes/Iconos/experiencia.png" alt="">
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


            <!-- referencias -->
            <h5 class="tituloReferencia mt-3"> Referencias <i style="font-size: 1rem;">(personales)</i></h5>


            <div class="subContenedorReferencia">

                <?php

                $queryReferencia = mysqli_query($conn, "SELECT * FROM referencia WHERE fk_id_curriculum  = $id_curriculum ORDER BY id_referencia");

                if (mysqli_num_rows($queryReferencia) == 0) {
                ?>
                    <div class="alert alert-warning" role="alert">
                        ¡Sin Referencia!
                    </div>
                    <?php


                } else {

                    while ($rowReferencia = mysqli_fetch_assoc($queryReferencia)) {
                    ?>

                        <div class="cartaReferencia">
                            <p><b><?php echo $rowReferencia['nombre_referente'] ?></b></p>
                            <p class="ocupacion_referencia"><?php echo $rowReferencia['cargo_referente'] ?></p>
                            <p><a target="_blank" href="https://wa.me/+593<?php echo $rowReferencia['numero_celular'] ?>"><?php echo $rowReferencia['numero_celular'] ?></a></p>
                            <p><?php echo $rowReferencia['correo_referente'] ?></p>

                        </div>

                <?php


                    }
                }
                ?>




            </div>

        </section>

        <!-- Educacion -->
        <section class="seccionEducacion">

            <h4>Educacion </h4>


            <!-- verificamos si tiene educacion, si no la tiene ponemos un texto por defecto -->
            <?php

            //hacemos una consulta solo para mostrar los datos de las experiencias que tiene
            $quieryConsultaEducacion = "SELECT * FROM educacion WHERE fk_id_curriculum = '$id_curriculum' ";
            $resultadoConsultaEducacion = mysqli_query($conn, $quieryConsultaEducacion);


            if (mysqli_num_rows($resultadoConsultaEducacion) == 0) {
            ?>
                <div class="alert alert-warning" role="alert">
                    ¡Sin Educación!
                </div>
                <?php


            } else {

                while ($mostrarDatosPadre = mysqli_fetch_array($resultadoConsultaEducacion)) {

                ?>

                    <!-- Carta eduacacion -->
                    <div class="contendor-educacion">

                        <div class="contenedor-imagen-educacion">
                            <img src="../../imagenes/Iconos/educacion.png" alt="">
                        </div>

                        <div class="contenedor-texto-educacion">
                            <h5><?php echo $mostrarDatosPadre['nombre_institucion'] ?></h5>
                            <p><?php echo "<b>Especializacion: </b>", $mostrarDatosPadre['especializacion']  ?> </p>
                            <span><?php echo "<b>Fecha de culminacion: </b>", $mostrarDatosPadre['fecha_culminacion'] ?></span>
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
                if ($recorrerDatosEstudiantes['estado_trabajo'] != null || $recorrerDatosEstudiantes['estado_trabajo'] != "") {
                ?>

                <?php
                }

                ?>

            </h4>


            <?php

            //consulta para mostrar los idiomas
            $quieryConsultaIdioma = "SELECT id_idioma, idioma, nivel FROM idioma WHERE fk_id_curriculum = '$id_curriculum' ";
            $resultadoConsultaIdioma = mysqli_query($conn, $quieryConsultaIdioma);

            if (mysqli_num_rows($resultadoConsultaIdioma) == 0) {
            ?>
                <div class="alert alert-warning" role="alert">
                    ¡Sin Idiomas!
                </div>
                <?php


            } else {

                while ($mostrarDatosIdioma = mysqli_fetch_array($resultadoConsultaIdioma)) {

                ?>

                    <!-- Carta eduacacion -->
                    <div class="contendor-educacion">

                        <div class="contenedor-imagen-educacion">
                            <img src="../../imagenes/logoIdioma.png" alt="">
                        </div>

                        <div class="contenedor-texto-educacion">
                            <h5><?php echo $mostrarDatosIdioma['idioma'] ?></h5>
                            <p><?php echo $mostrarDatosIdioma['nivel'] ?></p>

                        </div>
                    </div>


            <?php

                }
            }
            ?>

        </section>

        <!-- CONOCIMIENTOS Y APTITUDES -->
        <section class="seccionConocimientoAptitudes">

            <div class="tituloConocimiento">

                <h4> Conocimientos y Aptitudes </h4>

            </div>

            <div class="contenedor-conocimiento">

                <?php
                $queryConsultarConocimiento = "SELECT * FROM conocimientos WHERE fk_id_curriculum = '$id_curriculum' GROUP BY id_conocimientos DESC  ";
                $respuestaConocimiento = mysqli_query($conn, $queryConsultarConocimiento);


                if (mysqli_num_rows($respuestaConocimiento) == 0) {
                ?>
                    <div class="alert alert-warning" role="alert">
                        ¡Sin Aptitudes!
                    </div>
                    <?php


                } else {

                    while ($recorrerConocimiento = mysqli_fetch_array($respuestaConocimiento)) {

                        // comprovar si existe algun datos 
                        if ($recorrerConocimiento['id_conocimientos'] != null || $recorrerConocimiento['id_conocimientos'] != " ") {
                    ?>

                            <div class="conocimiento">
                                <span><?php echo $recorrerConocimiento['nombre_conocimiento'] ?></span>
                            </div>

                        <?php
                        } else {
                        ?>
                            <h3>No hay ningun conocimiento</h3>
                <?php
                        }
                    }
                }
                ?>

            </div>

            <div class="contenedorPortafolio">
                <div class="miPortafolio">

                    <span>Visita mi portafolio:</span>
                    <a href="<?php echo $recorrerDatosEstudiantes['portafolio'] ?>" target=”_blank”> <?php echo limitar_cadena($recorrerDatosEstudiantes['portafolio'], 40, '...') ?> </a>

                </div>
            </div>


            <!-- LINK DE CERTIFICADOS -->
            <div class="contenedorPortafolio">

                <div class="miPortafolio">

                    <?php
                    $queryBuscarCertificado = mysqli_query($conn, "SELECT otrosLinks FROM curriculum WHERE fk_id_usuEstudiantes = '$id_aspirante' ");
                    $recorrerCertificado = mysqli_fetch_array($queryBuscarCertificado);

                    // entra al if si exite el link del certificado
                    if ($recorrerCertificado['otrosLinks'] != null || $recorrerCertificado['otrosLinks'] != '') {
                    ?>
                        <span>
                            Mis certificados...

                            <a href="<?php echo $recorrerCertificado['otrosLinks'] ?>" target="_blank"><?php echo limitar_cadena($recorrerCertificado['otrosLinks'], 40, '...') ?></a>
                        </span>
                    <?php
                    } else {
                    ?>
                        <span>
                            Sin Certificados externos
                        </span> <br>
                    <?php
                    }
                    ?>

                </div>
            </div>

        </section>


    </main>



    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- VALIDAR FORMULARIO VACIOS -->
    <script src="../LOGIN/scriptValidarFormulario.js"></script>

    <!-- evitar reenvio de formulario -->
    <script src="../../evitarReenvioFormulario.js"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- LIBRERIA ALERTA -->
    <script src="./modalGuardado.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>


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

        //guardar el aspirante
        const guardarAspirante = (id_aspirante, id_oferta) => {

            let imagenGuardar = document.getElementById('imagenGuardar')


            let FD = new FormData()
            FD.append('id_aspirante', id_aspirante)
            FD.append('id_oferta', id_oferta)


            fetch('./queryGuardarAspirante.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    if (data.mensaje === 'ok') {

                        modalGuardado('Guardado Correctamente')
                        imagenGuardar.src = "../../imagenes/Iconos/desguadar.webp"
                        setTimeout(() => {
                            window.location.reload()
                        }, 1500)

                    }

                })


        }


        //guardar el aspirante
        const desguardarAspirante = (id_aspirante, id_oferta) => {

            let imagenDesguardar = document.getElementById('imagenDesguardar')


            let FD = new FormData()
            FD.append('id_aspirante', id_aspirante)
            FD.append('id_oferta', id_oferta)


            fetch('./queryDesguardarAspirante.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    if (data.mensaje === 'ok') {
                        modalGuardado('Eliminado Correctamente')
                        imagenDesguardar.src = "../../imagenes/Iconos/guardar.webp"
                        setTimeout(() => {
                            window.location.reload()
                        }, 1500)
                    }

                })


        }

        // desaprobar al aspirnate
        const desaprobar = (id_postula, id_oferta, puesto, empresa, correo) => {

            let FD = new FormData()
            FD.append('id_postula', id_postula)
            FD.append('id_oferta', id_oferta)
            FD.append('puesto', puesto)
            FD.append('empresa', empresa)
            FD.append('correo', correo)



            fetch('./queryDesaprobar.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    console.log(data);
                    if (data.mensaje === 'ok') {

                        modalGuardado('Desaprobado Correctamente')

                        setTimeout(() => {
                            window.location.reload()
                        }, 1500)
                    }

                })


        }
    </script>



</body>

</html>