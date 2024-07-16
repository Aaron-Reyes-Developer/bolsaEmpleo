<?php


session_start();
$id_aspirante = $_SESSION['id_aspirantes'];


//si se intenta ingresar sin iniciar sesion
if ($id_aspirante == null) {
    header('Location: ../../LOGIN/login.php');
    die();
}

include("../../conexion.php");
include("../../funciones.php");


// eliminar la session de el id_oferta para que no halla ningun inconveniente
if (isset($_SESSION['id_oferta'])) {
    unset($_SESSION['id_oferta']);
}

// eliminar la session de la empresa
if (isset($_SESSION['id_empresa'])) {
    unset($_SESSION['id_empresa']);
}


$fecha_acual =  date('Y-m-d');

//////////////////////////////////////////////      LOGICA NOTIFICACION      //////////////////////////////////////////////

// NOTIFICACION (icono campana)
$queryNotificacion = mysqli_query($conn, "call notificacion('$id_aspirante');");
$n_r_notificacion = mysqli_num_rows($queryNotificacion);
while (mysqli_next_result($conn)) {;
}


// desaparecer el numero de la notificacion
if (isset($_GET['notifiUpdate'])) {

    $queryActualizarNotificacion = mysqli_query($conn, "UPDATE postula SET estado_noti = '0' WHERE fk_id_usuEstudiantes = '$id_aspirante' AND estado_noti = 1 ");
    header('Location: ./inicio.php');
}


// ---------------------------------------------------      FIN LOGICA NOTIFICACION --------------------------------------------







//////////////////////////////////////////////      LOGICA DATOS ASPIRANTE      //////////////////////////////////////////////

// DATOS DE EL ASPIRANTE
$queryCunsultaDatosPadre = mysqli_query($conn, "call datosMainEstudiante('$id_aspirante')");
$recorrerConsultaPadre = mysqli_fetch_array($queryCunsultaDatosPadre);

// Id de la carrera graduada
$id_carrera = $recorrerConsultaPadre['id_carrera'];

while (mysqli_next_result($conn)) {;
}

//consultar datos de mis postulaciones
$queryPostulacion = "call consultaOfertaEmpleoEstudiante('$id_aspirante')";
$respuestaPostulacion = mysqli_query($conn, $queryPostulacion);
while (mysqli_next_result($conn)) {;
}



// si se apreta el boton postularme
if (isset($_POST['Botonpostularme'])) {

    $id_oferta = $_REQUEST['id_oferta'];

    $id_empresa = $_REQUEST['id_empresa'];


    // ingresar oferta en la tabla postula
    $queryPostula = "INSERT INTO postula (fecha_postulacion, estado_noti_empresa ,fk_id_usuEstudiantes, fk_id_usuario_empresa, fk_id_oferta_trabajo) VALUES (current_timestamp(), 1,'$id_aspirante', '$id_empresa' ,'$id_oferta')";
    $respuestaPostula = mysqli_query($conn, $queryPostula);

    // si sale bien todo
    if ($respuestaPostula) {

        $_SESSION['postulado'] = true;

?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalPostulacionCorrecto.js"></script>

        </body>


<?php
    }
}

// ---------------------------------------------------      FIN LOGICA DATOS ASPIRANTE --------------------------------------------





// consulta para la mostrar los nombres de la empresa en el select del formulario 
$queryNombreEmpresa = mysqli_query($conn, "SELECT id_datos_empresa, nombre FROM datos_empresa");
$datosNombreEmpresa = array();
while ($recorrerNombreEmpresa = mysqli_fetch_array($queryNombreEmpresa)) {
    $datosNombreEmpresa[] = $recorrerNombreEmpresa;
}



//////////////////////////////////////////////      LOGICA PAGINACION      //////////////////////////////////////////////



// todas las ofertas sin limite
$queryTotalOferta = mysqli_query($conn, "SELECT COUNT(*) as totalOferta FROM oferta_trabajo WHERE fk_id_carrera = '$id_carrera' AND estado_oferta = 1");
$recorrerTotalOferta = mysqli_fetch_array($queryTotalOferta);
while (mysqli_next_result($conn)) {;
}



// datos para la paginacion
$totalOfertas = $recorrerTotalOferta['totalOferta'];
$limiteConsulta = 15;
if (empty($_GET['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_GET['pagina'];
}


// operacion
$desde = ($pagina - 1) * $limiteConsulta;
$total_paginas = ceil($totalOfertas / $limiteConsulta);

// ---------------------------------------------------      FIN LOGICA PAGINACION --------------------------------------------





//////////////////////////////////////////////      LOGICA OFERTAS / PUBLICADAD      //////////////////////////////////////////////


// query mostrar las ofertas
$queryOfertas = "SELECT 
oft.id_oferta_trabajo,
oft.puesto,
oft.precio,
oft.ubicacion_empleo,
oft.tareas_realizar,
oft.detalle,
oft.fecha_oferta,
oft.estado_oferta,
hor.id_tipo_horario_oferta,
hor.nombre as hora,
tip_ofert.id_tipo_oferta,
tip_ofert.nombre as tipo_oferta,
tip_lu_oft.id_tipo_lugar_oferta,
tip_lu_oft.nombre as tipo_lugar,
car.id_carrera,
car.nombre_carrera as nombre_carrera,
usuEm.id_usuario_empresa
FROM oferta_trabajo  oft
INNER JOIN tipo_horario_oferta hor
ON hor.id_tipo_horario_oferta = oft.fk_id_horario
INNER JOIN tipos_oferta tip_ofert
ON tip_ofert.id_tipo_oferta = oft.fk_id_tipo_oferta
INNER JOIN tipo_lugar_oferta tip_lu_oft
ON tip_lu_oft.id_tipo_lugar_oferta = oft.fk_id_tipo_lugar_oferta
INNER JOIN carreras car
ON car.id_carrera = oft.fk_id_carrera 
INNER JOIN usuario_empresa as usuEm
ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa
WHERE car.id_carrera = '$id_carrera' 
AND oft.estado_oferta = 1 
ORDER BY oft.id_oferta_trabajo DESC 
LIMIT $desde, $limiteConsulta ";


// query mostrar la publicidad
$queryPublicidad = mysqli_query($conn, "SELECT publi.*, car.nombre_carrera FROM publicidad publi INNER JOIN carreras car ON car.id_carrera = publi.fk_id_carrera WHERE fk_id_carrera = '$id_carrera' ");

// borra la publicidad si ya caduco
$queryEliminarPublicidad  = mysqli_query($conn, "DELETE FROM publicidad WHERE fecha_caducidad = '$fecha_acual' ");


// ---------------------------------------------------      FIN LOGICA OFERTA/PUBLICIDAD --------------------------------------------


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


    <link rel="stylesheet" href="estiloInicio.css">
    <title>Inicio</title>
</head>

<body>

    <header class="">
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <a class="navbar-brand" href="#">
                    <img src="../../imagenes/Iconos/iconoAdmin/iconoPaginas.gif" style="width: 50px;" alt="">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav">

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../INICIO/inicio.php"><img src="../../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../ENCONTRETRABAJO/encontreTrabajo.php"><img src="../../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa iconoCampana">

                            <?php

                            if ($n_r_notificacion >= 1) {


                            ?>
                                <div class="dropdown">

                                    <!-- icono campana con la notificacion -->
                                    <a class="iconoEnlace" data-bs-toggle="dropdown" aria-expanded="false">

                                        <img src="../../imagenes/Iconos/campana.svg" alt="">

                                        <div class="bg-danger rounded text-light numero_icono">
                                            <?php echo $n_r_notificacion ?>
                                        </div>
                                    </a>

                                    <ul class="dropdown-menu">

                                        <?php

                                        while ($recorrerNoti = mysqli_fetch_array($queryNotificacion)) {

                                        ?>
                                            <li>
                                                <a onclick="irOferta(<?php echo $recorrerNoti['id_oferta_trabajo'] ?>, <?php echo $recorrerNoti['id_usuario_empresa'] ?>)" class="dropdown-item notificacionTexto">
                                                    <?php echo "Aprobado en: " . "<b>" . $recorrerNoti['puesto'] . "</b>" ?>
                                                </a>
                                            </li>

                                        <?php
                                        }
                                        ?>

                                        <hr>
                                        <li><a class="dropdown-item marcarComoLeido" href="?notifiUpdate=ok">Marcar como leido</a></li>
                                        <li><a class="dropdown-item" href="../../VERNOTIFICACIONES/verNotificaciones.php">Ver todas las notifi...</a></li>
                                    </ul>
                                </div>
                            <?php


                            } else {
                            ?>
                                <div class="dropdown">

                                    <!-- icono campana con la notificacion -->
                                    <a class="iconoEnlace" data-bs-toggle="dropdown" aria-expanded="false">

                                        <img src="../../imagenes/Iconos/campana.svg" alt="">
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


                            <a class="nav-link iconoEnlace" aria-current="page" href="../../cerrarSesion.php"><img src="../../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav" style="width: 50px; height: 50px;">
                            <a class="nav-link enlace-avatar" aria-current="page" href="../perfilAspirante.php" style="padding: 0;"><img style="width: 100%; height: 100%; object-fit: cover;" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerConsultaPadre['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>


    <main class="main">

        <!-- MIS POTUALCIONES -->
        <section class="seccionMisPostulaciones">

            <h3><a href="../MISPOSTULACIONES/misPostulaciones.php">Mis postulaciones</a></h3>

            <div class="contenedorMisPostulaciones">

                <?php

                while ($recorrerPostulaciones = mysqli_fetch_array($respuestaPostulacion)) {
                ?>

                    <!-- carta -->
                    <div class="misPostulaciones">

                        <div class="detalles">

                            <h5><?php echo $recorrerPostulaciones['puesto'] ?></h5>
                            <ul>
                                <li><b>Empresa: </b> <?php echo $recorrerPostulaciones['nombre_empresa'] ?> </li>
                                <li><b>Ubicación: </b> <?php echo $recorrerPostulaciones['ubicacion_empleo'] ?> </li>
                                <li><b>Horario: </b> <?php echo $recorrerPostulaciones['horario'] ?> </li>
                                <li><b>Tipo: </b> <?php echo $recorrerPostulaciones['tipo_lugar'] ?> </li>
                                <li><b>Oferta: </b> <?php echo $recorrerPostulaciones['tipo_oferta'] ?></li>
                            </ul>

                        </div>


                        <a onclick="irOferta(<?php echo $recorrerPostulaciones['id_oferta_trabajo'] ?>, <?php echo $recorrerPostulaciones['id_usuario_empresa'] ?>)" style="color: blue; text-decoration: underline; cursor: pointer;"> Ver Detalles... </a>
                    </div>

                <?php
                }


                ?>

            </div>

        </section>


        <!--OFERTAS  -->
        <section class="seccionOfertasDeEmpleo">

            <h1>Ofertas de Empleo / Vacantes (<?php echo $totalOfertas ?>)</h1>
            <hr>

            <!-- Se muestra un link cunado el tamaño sea de un celular -->
            <div class="MostraMis">
                <a href="../MISPOSTULACIONES/misPostulaciones.php">Mis postulaciones...</a>
                <a href="../MISPOSTULACIONES/misPostulaciones.php">Mostrar mis ofertas aprobadas...</a>
            </div>


            <!-- FORMULARIO DE BUSQUEDAS -->
            <div>

                <div class="contenedorFormulario">

                    <!-- BUSCAR -->
                    <form class="formularioBuscar formulario" id="fomulario">

                        <div class="mb-3 ">

                            <!-- buscar por cargo de oferta-->
                            <div class="subContenedorImputs mb-3">
                                <input type="text" class="form-control inputBuscar" name="cargo" id="buscar" placeholder="Cargo de la oferta">
                            </div>

                            <!-- filtar por estado de empleo -->
                            <div class="mb-3 subContenedorImputs subContenedorImputFiltrar">

                                <select class="form-select seleccionFiltrar" id="filtrarEstado" name="filtrarEstado" aria-label="Default select example">

                                    <option selected value="" disabled>Filtrar por Estado</option>

                                    <?php

                                    // mustra las opciones con los datos de la bd
                                    $queryTipoOferta = mysqli_query($conn, "SELECT * FROM tipos_oferta ORDER BY id_tipo_oferta DESC");

                                    while ($recorrerTipoOferta = mysqli_fetch_array($queryTipoOferta)) {
                                    ?>

                                        <option value="<?php echo $recorrerTipoOferta['nombre'] ?>"><?php echo $recorrerTipoOferta['nombre'] ?></option>

                                    <?php
                                    }
                                    ?>
                                </select>


                            </div>


                            <!-- filtrar por empresa -->
                            <div class="mb-3 subContenedorImputs subContenedorImputFiltrar">

                                <select class="form-select seleccionFiltrar" id="filtrarEmpresa" name="filtrarEmpresa" aria-label="Default select example">
                                    <option selected value="" disabled>Filtrar por Empresa</option>

                                    <?php
                                    foreach ($datosNombreEmpresa as $e) {
                                    ?>
                                        <option value="<?php echo $e['nombre'] ?>"><?php echo $e['nombre'] ?></option>
                                    <?php
                                    }
                                    ?>


                                </select>


                            </div>


                            <!-- filtrar por Ciudad -->
                            <div class="mb-3 subContenedorImputs subContenedorImputFiltrar">

                                <select class="form-select seleccionFiltrar" id="filtrarCiudad" name="filtrarCiudad" aria-label="Default select example">
                                    <option selected value="" disabled>Filtrar por Ciudad</option>

                                    <?php
                                    $queryUbicacionEmpleo = mysqli_query($conn, "SELECT ubicacion_empleo FROM oferta_trabajo WHERE estado_oferta = 1 GROUP BY ubicacion_empleo");

                                    while ($recorrerUbicacion = mysqli_fetch_assoc($queryUbicacionEmpleo)) {
                                    ?>
                                        <option value="<?php echo $recorrerUbicacion['ubicacion_empleo'] ?>"><?php echo $recorrerUbicacion['ubicacion_empleo'] ?></option>
                                    <?php
                                    }

                                    ?>


                                </select>


                            </div>

                            <input type="submit" value="Buscar" class="btn btn-primary botonBuscar">
                        </div>



                    </form>

                </div>
            </div>



            <!-- OFERTAS -->
            <div class="seccionOfertas" id="seccionOfertas" data-p="1">


                <!-- PUBLICIDAD -->
                <?php

                // muestra la publicidad
                $contador = 0;
                while ($recorrerPublicidad = mysqli_fetch_assoc($queryPublicidad)) {
                    $contador += 1;
                ?>
                    <h5 id="tituloPublicidad">UNESUM <img src="../../imagenes/Iconos/verificado.png" width="15px" alt=""></h5>

                    <div class="contenedorPublicidad mb-3" id="publicidad<?php echo $contador ?>">

                        <span class="cerrar" onclick="cerrarPublicidad('publicidad<?php echo $contador ?>')">X</span>

                        <p id="detalleMostrar">
                            <?php echo $recorrerPublicidad['detalle'] ?>
                        </p>


                        <?php
                        // si existe el link
                        if ($recorrerPublicidad['link'] != '') {
                        ?>
                            <a target="_blank" href="<?php echo $recorrerPublicidad['link'] ?>" class="link">Ir</a>
                        <?php
                        }
                        ?>

                        <span class="fecha_caducidad"> <b>Caduca:</b> <?php echo $recorrerPublicidad['fecha_caducidad'] ?> </span>


                    </div>

                <?php
                }



                // query mostrar ofertas (arriba esta el string)
                $respuestaOfertas = mysqli_query($conn, $queryOfertas);
                while (mysqli_next_result($conn)) {;
                }

                while ($recorrerOfertas = mysqli_fetch_array($respuestaOfertas)) {

                    // buscar si en la oferta ya se postulo el aspirante para desactivar el boton de "postular"
                    $id_oferta = $recorrerOfertas['id_oferta_trabajo'];

                    $resultadoBuscarPostulanteEnOferta = mysqli_query($conn, "SELECT * FROM postula WHERE fk_id_oferta_trabajo = '$id_oferta' and fk_id_usuEstudiantes = '$id_aspirante' ");

                ?>

                    <!-- carta -->
                    <div class="oferta">

                        <h4><?php echo $recorrerOfertas['puesto'] ?></h4>
                        <p><?php echo limitar_cadena($recorrerOfertas['detalle'], 170, '...') ?></p>

                        <ul class="ul_ofertas">
                            <li><b>Lugar: </b> <?php echo $recorrerOfertas['ubicacion_empleo'] ?></li>
                            <li><b>Tipo: </b> <?php echo $recorrerOfertas['tipo_lugar'] ?></li>
                            <li><b>Oferta: </b> <?php echo $recorrerOfertas['tipo_oferta'] ?></li>
                        </ul>

                        <div class="detalles_postular">

                            <!-- id oferta y puesto-->
                            <?php
                            $id_oferta = $recorrerOfertas['id_oferta_trabajo'];
                            $id_empresa = $recorrerOfertas['id_usuario_empresa'];
                            $puesto = $recorrerOfertas['puesto'];

                            ?>

                            <a onclick="irOferta(<?php echo $id_oferta ?>, <?php echo $id_empresa ?>)" style="color: blue; text-decoration: underline; cursor: pointer;">Ver detalles...</a>


                            <!-- si ya esta postulado en la oferta desaparece el boton -->
                            <?php
                            if (mysqli_num_rows($resultadoBuscarPostulanteEnOferta) == 0) {
                            ?>
                                <form action="./inicio.php?id_oferta=<?php echo $id_oferta ?>&id_empresa=<?php echo $id_empresa ?>" method="post" class="formularioPostular">
                                    <input type="submit" name="Botonpostularme" value="Postularme" class="btn btn-primary Botonpostularme">
                                </form>

                            <?php
                            } else {
                                echo "postulado";
                            }
                            ?>


                        </div>

                    </div>


                <?php
                }
                ?>



            </div>


            <!-- PAGINACION PHP -->
            <div class="contenedorPaginacion" id="contenedorPaginacion">

                <nav aria-label="Page navigation example">
                    <ul class="pagination">

                        <!--la paginacion funciona capturando el dato de paginacion que viene en la url,
                            hacemos un for para mostrar el numero de paginas que hay, se descativa la flecha ('>>') 
                            cunado el limite de pagina sobrepase    
                        -->
                        <?php

                        $i = 0;
                        $limitePaginacion = 7;
                        $limitacion = false;
                        for ($i; $i < $total_paginas; $i++) {

                            // pregunta si 'i' es menor que la limitacion, 
                            //si es verdad entonces entra en la paginacion, 
                            //si no es verdad detiene la paginacion y pone un (..)
                            //para indicar que hay mas paginas
                            if ($i < $limitePaginacion) {
                                $limitacion = true;
                        ?>
                                <li class="page-item <?php if ($pagina == $i + 1) echo 'active' ?>"><a class="page-link" href="?pagina=<?php echo $i + 1 ?>"><?php echo $i + 1 ?></a></li>
                            <?php

                            }
                        }

                        // para poner la limitacion de la paginacion (...)
                        if ($limitacion) {
                            ?>
                            <li class="page-item"><a class="page-link disabled" href="">...</a></li>
                        <?php
                        }

                        ?>


                        <li>
                            <a class="page-link <?php if ($pagina > $i - 1) echo 'disabled' ?>" href="?pagina=<?php echo $pagina + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>


                    </ul>
                </nav>

            </div>

            <!-- PAGINACION JS -->
            <div class="contenedorPaginacionJs" id="contenedorPaginacionJs" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 1rem;">
                <button id="botonRetroceder" onclick="anteriorPagina()" class="btn " style="background-color: #04ec64; border: 1px solid #000;">...Retroceder</button>
                <span id="paginaActualImprimir">1</span>
                <button id="botonSiguiente" onclick="siguientePagina()" class="btn " style="background-color: #04ec64; border: 1px solid #000;">Siguiente...</button>
            </div>

        </section>


        <!-- APROBADAS -->
        <section class="seccionNoSe">

            <h3>Aprobadas</h3>

            <div class="contenedorMisPostulaciones">

                <?php
                //mostrar las postulaciones aprobadas
                $queryPostulacionesAprobadas = "call postulacionAprobada('$id_aspirante') ";
                $respuestaPostulacionesAprobadas = mysqli_query($conn, $queryPostulacionesAprobadas);

                while ($recorrerPostulacionesAprobadas = mysqli_fetch_array($respuestaPostulacionesAprobadas)) {
                ?>
                    <!-- carta -->
                    <div class="misPostulaciones postulacionAprobada">

                        <div class="detalles">

                            <h5> <?php echo $recorrerPostulacionesAprobadas['puesto'] ?> </h5>
                            <ul>
                                <li><b>Empresa: </b> <?php echo $recorrerPostulacionesAprobadas['nombreUsuario'] ?> </li>
                                <li><b>Ubicación: </b> <?php echo $recorrerPostulacionesAprobadas['ubicacion_empleo'] ?> </li>
                                <li><b>Horario: </b> <?php echo $recorrerPostulacionesAprobadas['horario'] ?> </li>
                                <li><b>Tipo: </b> <?php echo $recorrerPostulacionesAprobadas['tipo_lugar'] ?> </li>
                                <li><b>Oferta: </b> <?php echo $recorrerPostulacionesAprobadas['tipo_oferta'] ?> </li>
                            </ul>

                        </div>
                        <a onclick="irOferta(<?php echo $recorrerPostulacionesAprobadas['id_oferta_trabajo'] ?>, <?php echo $recorrerPostulacionesAprobadas['id_usuario_empresa'] ?>)" style="color: blue; text-decoration: underline; cursor: pointer;">Ver Detalles...</a>
                    </div>
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
    <script src="../../LOGIN/scriptValidarFormulario.js"></script>


    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <!-- evitar el reenvio de los formularios -->
    <script src="../../evitarReenvioFormulario.js"></script>


    <script>
        // formulario para buscar empleo
        const fomulario = document.getElementById('fomulario')
        const seccionOfertas = document.getElementById('seccionOfertas')
        const contenedorPaginacion = document.getElementById('contenedorPaginacion')
        const contenedorPaginacionJs = document.getElementById('contenedorPaginacionJs')
        const botonRetroceder = document.getElementById('botonRetroceder')
        const botonSiguiente = document.getElementById('botonSiguiente')
        const paginaActualImprimir = document.getElementById('paginaActualImprimir')

        // por defecto se desaparece el boton retroceder
        botonRetroceder.style.display = 'none'


        // captura la pagina actual
        let pagina = seccionOfertas.dataset.p


        // desaparecer el paginador js
        contenedorPaginacionJs.style.display = 'none'


        // cuando se haga submit en el formulario
        fomulario.addEventListener('submit', function(e) {

            e.preventDefault()

            // muestra el boton retoceder cuando la pagina esta desde el 2 pa delante
            if (pagina > 1) {
                botonRetroceder.style.display = 'block'
            }

            // esta funcion captura los datos del formulario y llama a la funcion para pedir la peticion a php
            obtenerDatosInputs()
        })


        // captura los datos del formulario y llama a la funcion para pedir la peticion a php
        const obtenerDatosInputs = _ => {

            let buscar = document.getElementById('buscar').value //por el nombre de la ofera
            let filtrarEstado = document.getElementById('filtrarEstado').value //por el estado (pasantia, colaborador, oferta)
            let filtrarEmpresa = document.getElementById('filtrarEmpresa').value //por empresa
            let filtrarCiudad = document.getElementById('filtrarCiudad').value // por ciudad


            // manda el query para completarlo con php
            buscarQuery = buscar != '' ? `AND oft.puesto LIKE '%${buscar}%' ` : ''
            filtrarEstadoQuery = filtrarEstado != '' ? `AND tip_ofert.nombre = '${filtrarEstado}' ` : ''
            filtrarEmpresaQuery = filtrarEmpresa != '' ? `AND dt.nombre LIKE '${filtrarEmpresa}' ` : ''
            filtrarCiudadQuery = filtrarCiudad != '' ? `AND oft.ubicacion_empleo = '${filtrarCiudad}' ` : ''


            let query = buscarQuery + filtrarEstadoQuery + filtrarEmpresaQuery + filtrarCiudadQuery
            let id_carrera = '<?php echo $id_carrera  ?>'


            let FD = new FormData()
            FD.append('query', query)
            FD.append('pagina', pagina)
            FD.append('id_carrera', id_carrera)

            // llama a la peticion que muestra las ofertas en pantalla
            peticion(FD)

        }


        // HACER LA PETICION Y MUESTRA LAS OFERTAS EN PANTALLA
        function peticion(FD) {


            let id_aspirante = <?php echo $id_aspirante ?>

            // muestra los datos en pantalla
            fetch('./consultaBuscar.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {

                    // oculta el boton siguiente  si no hay mas datos
                    if (e.length == 0) {
                        botonSiguiente.style.display = 'none'

                        seccionOfertas.innerHTML = `
                            <div class="alert alert-warning" role="alert">
                                No exiten ofertas
                            </div>
                        `
                    }



                    // existen datos
                    if (e.length > 0) {

                        // muestra el boton siguiente
                        botonSiguiente.style.display = 'block'
                        seccionOfertas.innerHTML = ''
                        contenedorPaginacion.style.display = 'none'
                        contenedorPaginacionJs.style.display = 'flex'


                        e.forEach(recorrer => {

                            // muestra si ya esta postulado en la oferta
                            mostrarOfertaPostulada(id_aspirante, recorrer.id_oferta_trabajo).then(evento => {

                                let formularioPostularme = evento.mensaje !== 'Postulado' ? `
                                <form action="./inicio.php?id_oferta=${recorrer.id_oferta_trabajo}&id_empresa=${recorrer.id_usuario_empresa}" method="post" class="formularioPostular">
                                    <input type="submit" name="Botonpostularme" value="Postularme" class="btn btn-primary Botonpostularme">
                                </form>` : 'Postulado'

                                seccionOfertas.innerHTML += `

                                    <div class="oferta">

                                        <h4>${recorrer.puesto}</h4>
                                        <p>${recorrer.detalle}</p>

                                        <ul class="ul_ofertas">
                                            <li><b>Lugar: </b> ${recorrer.ubicacion_empleo}</li>
                                            <li><b>Tipo: </b> ${recorrer.tipo_lugar}</li>
                                            <li><b>Oferta: </b>${recorrer.tipo_oferta}</li>
                                        </ul>

                                        <div class="detalles_postular">

                                            <a onclick="irOferta(${recorrer.id_oferta_trabajo}, ${recorrer.id_usuario_empresa})" style="color: blue; text-decoration: underline; cursor: pointer;" >Ver detalles...</a>


                                            <!-- si ya esta postulado en la oferta desaparece el boton -->
                                            
                                            ${formularioPostularme}
                                        </div>

                                    </div>

                                `
                            })

                        })

                    }
                })



        }


        // peticion para ver si ya esta postulado
        async function mostrarOfertaPostulada(id_aspirante, id_oferta) {

            let formdata = new FormData()
            formdata.append('id_aspirante', id_aspirante)
            formdata.append('id_oferta', id_oferta)

            let respuesta = await fetch('./consultaMostrarOfertaPostulada.php', {
                method: 'POST',
                body: formdata
            })

            return respuesta.json()

        }


        // cuando se aprete el boton siguiente
        const siguientePagina = _ => {

            // aumenta la pagina
            seccionOfertas.dataset.p = ++pagina

            // aparece el boton retroceder cuando la pagina este desde el 2 pa delante
            if (pagina >= 1) {
                botonRetroceder.style.display = 'block'
            }

            // muestra la pagina actual (el que esta en medio de los botones)
            paginaActualImprimir.innerHTML = pagina

            // esta funcion captura los datos del formulario y llama a la funcion para pedir la peticion a php e imprimirla 
            obtenerDatosInputs()
        }


        // cuando se aprete el boton aterior
        const anteriorPagina = _ => {

            // aumenta la pagina
            seccionOfertas.dataset.p = --pagina

            // desaparece la pagina retroceder cuando la pagina este en 1
            if (pagina <= 1) {
                botonRetroceder.style.display = 'none'
            }

            // muestra la pagina actual (el que esta en medio de los botones)
            paginaActualImprimir.innerHTML = pagina

            // esta funcion captura los datos del formulario y llama a la funcion para pedir la peticion a php e imprimirla 
            obtenerDatosInputs()
        }



        // cerrar la publicidad
        const cerrarPublicidad = id => {
            document.getElementById('tituloPublicidad').remove()
            document.getElementById(id).remove()


        }


        // ir a el detalle de la oferta
        const irOferta = (id_oferta, id_empresa) => {

            FD_ir = new FormData()
            FD_ir.append('id_oferta', id_oferta)



            fetch('../../queryIrOferta.php', {
                    method: 'POST',
                    body: FD_ir
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = `../../DETALLEOFERTA/detalleOferta.php?id_empresa=${id_empresa}`
                    }

                })
        }
    </script>
</body>

</html>