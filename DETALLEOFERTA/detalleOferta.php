<?php

include("../conexion.php");
session_start();
// error_reporting(0);


if (!isset($_SESSION['id_aspirantes']) || $_SESSION['id_aspirantes'] == "") {
    header("Location: ../LOGIN/login.php");
    die();
}

// datos
$id_aspirante = $_SESSION['id_aspirantes'];
$id_oferta = $_SESSION['id_oferta'];
$id_empresa = $_REQUEST['id_empresa'] ?? 0;



// sacamos la foto de perfil del header
$queryDatosAspirante = "call datosMainEstudiante('$id_aspirante')";
$respuestaDatosAspirante = mysqli_query($conn, $queryDatosAspirante);
$recorrerDatosAspirante = mysqli_fetch_array($respuestaDatosAspirante);


while (mysqli_next_result($conn)) {;
}


// consultamos los datos de la oferta 
$respuestaOferta = mysqli_query($conn, "call detalleOferta($id_empresa, $id_oferta) ");
$recorrerOferta = mysqli_fetch_array($respuestaOferta);
$id_carrera = $recorrerOferta['id_carrera'];
while (mysqli_next_result($conn)) {;
}


// datos empresa
$respuestaDatosEmpresa = mysqli_query($conn, "SELECT * FROM datos_empresa WHERE fk_id_usuario_empresa = '$id_empresa' ");
$recorrerDatosEmpresa = mysqli_fetch_array($respuestaDatosEmpresa);
while (mysqli_next_result($conn)) {;
}


// consulta para saber si la oferta esta aprobada
$respuestaOfertaAprobada = mysqli_query($conn, "SELECT aprobado FROM postula WHERE fk_id_oferta_trabajo = '$id_oferta' AND fk_id_usuEstudiantes = '$id_aspirante' ");
$recorrerOfertaAprobada = mysqli_fetch_array($respuestaOfertaAprobada);
while (mysqli_next_result($conn)) {;
}


//POSTULARME
if (isset($_POST['botonPostularme'])) {

    // ingresar oferta en la tabla postula
    $queryPostula = "INSERT INTO postula (fecha_postulacion, fk_id_usuEstudiantes, fk_id_oferta_trabajo) VALUES (current_timestamp(), '$id_aspirante', '$id_oferta')";
    $respuestaPostula = mysqli_query($conn, $queryPostula);

    // si sale bien todo
    if ($respuestaPostula) {

?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalPostulacionCorrecto.js"></script>

        </body>


    <?php
    }
}


// ELIMINAR POSTULACION
if (isset($_REQUEST['eliminar_postulacion'])) {

    $id_postula = $_REQUEST['eliminar_postulacion'];

    // query eliminar postulacion
    $queryEliminarPostulacion = mysqli_query($conn, "DELETE FROM postula WHERE id_postula = '$id_postula' ");


    if ($queryEliminarPostulacion) {
    ?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalEliminarPostulacion.js"></script>

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

    <link rel="stylesheet" href="estiloOferta.css">
    <title>Detalle Oferta</title>
</head>

<body>

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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../PERFILASPIRANTE/INICIO/inicio.php"><img src="../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="#"><img src="../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../cerrarSesion.php"><img src="../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav" style="width: 50px; height: 50px;">
                            <a class="nav-link enlace-avatar" aria-current="page" href="../PERFILASPIRANTE/perfilAspirante.php" style="padding: 0;"><img style="width: 100%; height: 100%; object-fit: cover;" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerDatosAspirante['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="main">

        <!-- DETALLE DE LA OFERTA -->
        <section class="seccionDetalleOferta">

            <?php

            // si no existe la oferta mandamos un 404
            if ($recorrerOferta['puesto'] == null || $recorrerOferta['puesto'] == "") {
                include('../ERROR404/error404.html');
                die();
            }
            ?>



            <h4><?php echo $recorrerOferta['puesto'] ?></h4>
            <hr>


            <?php

            //consulta para saber si el estudiante ya se postulo
            $respuestaYaSePostulo = mysqli_query($conn, "SELECT * FROM postula WHERE fk_id_oferta_trabajo = '$id_oferta' and fk_id_usuEstudiantes = '$id_aspirante' ");
            $contar_row_YaSePostulo = mysqli_num_rows($respuestaYaSePostulo);
            $recorrerYaSePostulo = mysqli_fetch_array($respuestaYaSePostulo);

            // preguntar si la oferta esta aprobada
            if ($recorrerOfertaAprobada['aprobado'] == 1) {

            ?>
                <div class="contenedorAprobado">
                    <h3>¡Aprobado!</h3>
                    <span>Este al pendiente de su correo electronico o su numero de celular</span>
                </div>

            <?php

                // si ya se postulo no se muestra el boton
            } else if ($contar_row_YaSePostulo > 0) {

                echo "Pendiente";

                // se muestra el boton de anular postulacion
            ?>
                <a class="btn btn-danger mx-3" href="?eliminar_postulacion=<?php echo $recorrerYaSePostulo['id_postula'] ?>">Anular Postulacion</a>
            <?php

            } else {
            ?>
                <form action="" method="post">
                    <input type="submit" name="botonPostularme" value="Postularme" class="botonPostularme btn btn-primary">
                </form>
            <?php
            }

            ?>

            <hr>

            <!-- CONTENEDOR DATOS PRINCIPALES -->
            <div class="contenedoFechas">

                <!-- fecha -->
                <div class="fecha">
                    <span> <b>Fecha: </b> <?php echo $recorrerOferta['fecha_oferta'] ?> </span>
                </div>


                <!-- Horario -->
                <div class="horario">
                    <span> <b>Horario: </b> <?php echo $recorrerOferta['hora'] ?> </span>
                </div>


                <!-- Empresa -->
                <div class="empresa">
                    <?php
                    $_SESSION['id_empresa'] = $recorrerDatosEmpresa['fk_id_usuario_empresa'];
                    ?>
                    <span>
                        <b>Empresa: </b>
                        <a href="../PERFILESPUBLICOS/PERFILEMPRESA/perfilEmpresa.php">
                            <?php echo $recorrerDatosEmpresa['nombre'] ?>
                        </a>
                        <img src="../imagenes/Iconos/verificado.png" width="15px" alt="">
                    </span>
                </div>


                <!-- Ubicacion -->
                <div class="Ubicacion">
                    <span> <img src="../imagenes/Iconos/iconoMaps.png" title="icono sacado de: www.flaticon.es" alt="icono sacado de: www.flaticon.es"> <b>Ubicacion: </b> <?php echo $recorrerOferta['ubicacion_empleo'] ?> </span>
                </div>
            </div>

            <!-- CONTENDOR DETALLES -->
            <div class="detalles">


                <h5>Detalles</h5>

                <div class="subContenedorDetalles">

                    <!-- DETALLE -->
                    <div>
                        <span class="detalleParrafo"> <?php echo $recorrerOferta['detalle'] ?> </span><br>
                    </div>

                    <hr>

                    <!-- TAREAS A REAIZAR -->
                    <div>
                        <h5>Tareas a realizar</h5>
                        <span class="detalleParrafo"> <?php echo $recorrerOferta['tareas_realizar'] ?> </span><br>
                    </div>

                    <hr>

                    <!-- RETQUISITOS -->
                    <div>

                        <h5>Requisitos</h5>

                        <ul>

                            <?php

                            $queryRequisitos = mysqli_query($conn, "SELECT * FROM requisitos WHERE fk_id_oferta_trabajo = $id_oferta");

                            while ($rowRequisitos = mysqli_fetch_assoc($queryRequisitos)) {
                            ?>
                                <li> <?php echo $recorrerOferta['detalle'] ?> </li><br>
                            <?php
                            }
                            ?>

                        </ul>

                    </div>

                    <hr>


                    <!-- SUB DETALLES -->
                    <ul>
                        <li><b>Oferta: </b> <?php echo $recorrerOferta['tipo_oferta'] ?> </li>
                        <li><b>Tipo: </b> <?php echo $recorrerOferta['tipo_lugar'] ?></li>
                        <li><b>Dirigido: </b> <?php echo $recorrerOferta['nombre_carrera'] ?></li>


                        <!-- verificar si existe el precio, si no existe poner un mensaje por defecto -->
                        <?php
                        if ($recorrerOferta['precio'] != 0) {
                        ?>
                            <li style="color: green;"> <b>Sueldo: </b> $<?php echo $recorrerOferta['precio'] ?> </li>

                        <?php
                        } else {
                        ?>
                            <li> <b>Sueldo: </b> Privado </li>
                        <?php
                        }
                        ?>

                    </ul>

                </div>
            </div>


        </section>


        <!-- MAS OFERTAS -->
        <section class="seccionMasOfertas">

            <h4>Otras Ofertas</h4>
            <hr>

            <div class="contenedorMasOfertas">

                <?php


                // consultamos los datos de la oferta con limite
                $quieryOfertaLimite = "SELECT * FROM 
                oferta_trabajo  oft
                INNER JOIN tipos_oferta tip_oft
                ON tip_oft.id_tipo_oferta = oft.fk_id_tipo_oferta
                WHERE estado_oferta = 1 
                AND oft.id_oferta_trabajo <> $id_oferta
                AND oft.fk_id_carrera = $id_carrera
                ORDER BY fecha_oferta  
                DESC LIMIT 10 ";

                $respuestaOfertaLimite = mysqli_query($conn, $quieryOfertaLimite);



                while ($recorreroMasOfertas = mysqli_fetch_array($respuestaOfertaLimite)) {
                ?>
                    <!-- carta -->
                    <div class="cartaOferta">

                        <div>
                            <h5><?php echo $recorreroMasOfertas['puesto'] ?></h5>
                            <span> <b>Ubicación: </b> <?php echo $recorreroMasOfertas['ubicacion_empleo'] ?> </span><br>
                            <span> <b>Tipo: </b> <?php echo $recorreroMasOfertas['nombre'] ?> </span>
                        </div>

                        <a onclick="irOferta(<?php echo $recorreroMasOfertas['id_oferta_trabajo'] ?>, <?php echo $recorreroMasOfertas['fk_id_usuario_empresa'] ?>)" class="enlaceVerMas">Ver detalles...</a>
                    </div>

                <?php
                }

                ?>

            </div>


        </section>

    </main>









    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- VALIDAR FORMULARIO VACIOS -->
    <script src="../LOGIN/scriptValidarFormulario.js"></script>



    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <script src="../evitarReenvioFormulario.js"></script>


    <script>
        AOS.init();

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
                        window.location.href = `./detalleOferta.php?id_empresa=${id_empresa}`
                    }

                })
        }
    </script>
</body>

</html>