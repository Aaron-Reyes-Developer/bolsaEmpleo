<?php
// error_reporting(0);


session_start();


if (isset($_SESSION['id_empresa']) == null || $_SESSION['id_empresa'] == "") {
    header("Location: ../LOGIN/login.php");
    die();
}


include('../conexion.php');

$id_empresa = $_SESSION['id_empresa'];
$id_oferta = $_SESSION['id_oferta'];


//consultar datos de la oferta
$quieryOferta = mysqli_query($conn, "call detalleOferta($id_empresa,$id_oferta);");
$recorrerOferta = mysqli_fetch_array($quieryOferta);

while (mysqli_next_result($conn)) {;
}

// total aspirantes que estan el la oferta
$queryTotalAspirante = mysqli_query($conn, "SELECT usuEstu.id_usuEstudiantes FROM usuario_estudiantes as usuEstu 
LEFT JOIN postula as post 
ON usuEstu.id_usuEstudiantes = post.fk_id_usuEstudiantes
LEFT JOIN oferta_trabajo as ofert
ON ofert.id_oferta_trabajo = post.fk_id_oferta_trabajo
WHERE post.fk_id_oferta_trabajo = '$id_oferta' ");

// datos paginacion
if (empty($_REQUEST['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_REQUEST['pagina'];
}

$liminetaConsulta = 20;
$desde = ($pagina - 1) * $liminetaConsulta;
$totalAspirantes = mysqli_num_rows($queryTotalAspirante);
$totalPaginas = ceil($totalAspirantes / $liminetaConsulta);


//consulta todos los aspirantes que estan postulados en una oferta
$respuestaAspirantesEnOferta = mysqli_query($conn, "call mostrarTodosLosAspirantesDentroOferta('$id_oferta', $desde, $liminetaConsulta)");
while (mysqli_next_result($conn)) {;
}


// FOTO DE LA EMPRESA
$respuestaFotoEmpresa = mysqli_query($conn, "SELECT datosEm.imagen_perfil FROM usuario_empresa as usuEm
LEFT JOIN datos_empresa as datosEm
ON usuEm.id_usuario_empresa = datosEm.fk_id_usuario_empresa
WHERE usuEm.id_usuario_empresa = '$id_empresa' ");
$recorrerFotoEmpresa = mysqli_fetch_array($respuestaFotoEmpresa);

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

    <link rel="stylesheet" href="estiloVerOferta.css">
    <title>Ofertas con Aspirantes</title>
</head>

<body class="body">

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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../PERFILEMPRESA/INICIOEMPRESA/inicioEmpresa.php"><img src="../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page"><img src="../imagenes/Iconos/campana.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../cerrarSesion.php"><img src="../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav">
                            <a class="nav-link enlace-avatar" aria-current="page" href="../PERFILEMPRESA/perfilEmpresa.php"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerFotoEmpresa['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="main">

        <!--detalle -->
        <section class="seccionDetalle">

            <!-- TITULO -->
            <div class="contenedor_titulo">
                <hr>

                <?php

                // si por alguna razon la consulta no busca el dato que se le pasa por la ulr, se muestra un mensaje de error
                if (mysqli_num_rows($quieryOferta) <= 0 || $recorrerOferta['estado_oferta'] == 0) {
                    include('../ERROR404/error404.html');
                    die();
                }
                ?>


                <h2><?php echo $recorrerOferta['puesto'] ?></h2>
                <hr>
            </div>

            <!-- FECHAS -->
            <div class="contenedoFechas">

                <!-- fecha -->
                <div class="fecha">
                    <span> <b>Fecha: </b> <?php echo $recorrerOferta['fecha_oferta'] ?></span>
                </div>


                <!-- Horario -->
                <div class="horario">
                    <span> <b>Horario: </b> <?php echo $recorrerOferta['hora'] ?> </span>
                </div>

                <!-- Ubicacion -->
                <div class="Ubicacion">
                    <span> <img src="../imagenes/Iconos/iconoMaps.png" title="icono sacado de: www.flaticon.es" alt="icono sacado de: www.flaticon.es"> <b>Ubicacion: </b> <?php echo $recorrerOferta['ubicacion_empleo'] ?> </span>
                </div>

            </div>

            <!-- DETALLES -->
            <div class="detalles">


                <h5>Detalles</h5>

                <div class="subContenedorDetalles">

                    <!-- DETALLE -->
                    <div>
                        <span class="detalleParrafo"> <?php echo $recorrerOferta['detalle'] ?></span><br>
                    </div>

                    <hr>

                    <!-- TAREAS A REALIZAR -->
                    <div>
                        <h5>Tareas a realizar</h5>
                        <span class="detalleParrafo"> <?php echo $recorrerOferta['tareas_realizar'] ?></span><br>
                    </div>

                    <hr>

                    <!-- REQUISITOS -->
                    <div>
                        <h5>Requisitos</h5>

                        <ul style="list-style: unset; padding-left: 3rem;">

                            <?php

                            $queryRequisitos = mysqli_query($conn, "SELECT * FROM requisitos WHERE fk_id_oferta_trabajo = $id_oferta");

                            while ($rowRequisitos = mysqli_fetch_array($queryRequisitos)) {
                            ?>

                                <li> <?php echo $rowRequisitos['detalle'] ?></li>

                            <?php
                            }
                            ?>

                        </ul>


                    </div>

                    <hr>

                    <!-- DETALLES DEL EMPLEO (precio, carrera ...) -->
                    <ul>
                        <li><b>Oferta: </b> <?php echo $recorrerOferta['tipo_oferta'] ?> </li>
                        <li><b>Tipo: </b> <?php echo $recorrerOferta['tipo_lugar'] ?> </li>
                        <li><b>Dirigido: </b> <?php echo $recorrerOferta['nombre_carrera'] ?> </li>


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


        <section class="seccionPostulantes">

            <hr>

            <h2>Postulantes</h2>


            <div class="contenedorPostulantes">


                <?php

                //recorre todos los aspirantes que se encuentren en la oferta
                while ($recorrerAspirantesEnOferta = mysqli_fetch_array($respuestaAspirantesEnOferta)) {


                    //consulta los datos del aspirante
                    $id_aspirante = $recorrerAspirantesEnOferta['id_usuEstudiantes'];
                    $resultadoAspirante = mysqli_query($conn, "call datosMainEstudiante('$id_aspirante')");
                    while (mysqli_next_result($conn)) {;
                    }

                    // consulta para saber si esta aprobado
                    $aspiranteAprobado = mysqli_query($conn, "call aspiranteAprobado(1, '$id_aspirante', '$id_oferta', 1)");
                    $n_r_aspiranteAprobado = mysqli_num_rows($aspiranteAprobado);
                    while (mysqli_next_result($conn)) {;
                    }


                    //recorre los perfiles de el estudiante
                    while ($recorrerAspirante = mysqli_fetch_array($resultadoAspirante)) {
                ?>

                        <!-- CARTA ASPIRANTE-->
                        <div class="postulante <?php if ($n_r_aspiranteAprobado >= 1) echo 'aprobado' ?>">

                            <div class="contenedorImagenPostulante">
                                <img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerAspirante['imagen_perfil']) ?>" alt="">
                            </div>

                            <div class="informacionPostulante">

                                <h5> <?php echo $recorrerAspirante['nombre'] ?> </h5>

                                <ul>
                                    <li><b>Especializacion: </b> <?php echo $recorrerAspirante['especializacion_curriculum'] ?> </li>
                                    <li><b>Carrera: </b> <?php echo $recorrerAspirante['nombre_carrera'] ?> </li>
                                </ul>

                                <a onclick="irAspirante(<?php echo $recorrerAspirante['id_usuEstudiantes'] ?>)" href="">Ver detalles...</a>
                            </div>

                        </div>

                <?php
                    }
                }

                ?>


                <!-- PAGINACION -->
                <div class="paginacion">


                    <nav aria-label="Page navigation example">
                        <ul class="pagination">

                            <?php
                            $i = 0;
                            for ($i; $i < $totalPaginas; $i++) {
                            ?>

                                <li class="page-item"><a class="page-link <?php if ($i + 1 == $pagina) echo 'active' ?>" href="?pagina=<?php echo $i + 1 ?>"><?php echo $i + 1 ?></a></li>

                            <?php
                            }

                            ?>

                            <li class="page-item <?php if ($i <= $pagina) echo 'disabled' ?>">
                                <a class="page-link" href="?pagina=<?php echo $pagina + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>


        </section>

    </main>

    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- VALIDAR FORMULARIO VACIOS -->
    <script src="../LOGIN/scriptValidarFormulario.js"></script>



    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();


        // IR A PERFIL ASPIRANTE
        const irAspirante = id => {

            FD = new FormData();
            FD.append('id', id)

            fetch('queryIrAspirante.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = '../PERFILESPUBLICOS/PERFILASPIRANTE/perfilAspirante.php'
                    }


                })

        }
    </script>


    <script src="../evitarReenvioFormulario.js"></script>
</body>

</html>