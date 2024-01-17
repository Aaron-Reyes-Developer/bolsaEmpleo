<?php

session_start();

if (!isset($_SESSION['id_empresa']) || $_SESSION['id_empresa'] == "") {
    header("Location: ../../LOGIN/login.php");
    die();
}

include("../../conexion.php");

// eliminar la session de el id_oferta para que no halla ningun inconveniente
if (isset($_SESSION['id_oferta'])) {
    unset($_SESSION['id_oferta']);
}

// borrar el id aspirante por cualquier cosa
if (isset($_SESSION['id_aspirante'])) {
    unset($_SESSION['id_aspirante']);
}


$id_empresa = $_SESSION['id_empresa'];


//mostrar todos los datos de la empresa
$queryDatosEmpresa = "SELECT * FROM datos_empresa WHERE fk_id_usuario_empresa = '$id_empresa' ";
$respuestaDatosEmpresa = mysqli_query($conn, $queryDatosEmpresa);
while (mysqli_next_result($conn)) {;
}
$recorrerDatosEmpresa = mysqli_fetch_array($respuestaDatosEmpresa);




// OPERACION PAGINACION
$limiteConsulta = 8;

if (empty($_GET['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_GET['pagina'];
}

$desde = ($pagina - 1) * $limiteConsulta;




// mostrar todas las ofertas
$queryDatosOfertas = mysqli_query($conn, "SELECT usuEm.id_usuario_empresa, ofer.id_oferta_trabajo, ofer.puesto, ofer.detalle 
FROM usuario_empresa as usuEm
LEFT JOIN oferta_trabajo as ofer
ON usuEm.id_usuario_empresa = ofer.fk_id_usuario_empresa
WHERE usuEm.id_usuario_empresa = '$id_empresa' 
AND ofer.estado_oferta = 1 
ORDER BY ofer.fecha_oferta DESC
LIMIT $desde, $limiteConsulta");


// consulta sin limitaciones para saber la cantidad de ofertas
$queryTotalOfertas = mysqli_query($conn, "SELECT ofer.id_oferta_trabajo
FROM usuario_empresa as usuEm
LEFT JOIN oferta_trabajo as ofer
ON usuEm.id_usuario_empresa = ofer.fk_id_usuario_empresa
WHERE usuEm.id_usuario_empresa = '$id_empresa' 
AND ofer.estado_oferta = 1
ORDER BY ofer.id_oferta_trabajo");


// buscar por el nombre del puesto
if (!empty($_REQUEST['buscar'])) {

    $buscar = htmlspecialchars($_REQUEST['buscar']);
    $queryDatosOfertas = mysqli_query($conn, "SELECT usuEm.id_usuario_empresa, ofer.id_oferta_trabajo, ofer.puesto, ofer.detalle 
                                            FROM usuario_empresa as usuEm
                                            LEFT JOIN oferta_trabajo as ofer
                                            ON usuEm.id_usuario_empresa = ofer.fk_id_usuario_empresa
                                            WHERE usuEm.id_usuario_empresa = '$id_empresa' 
                                            AND ofer.puesto LIKE '%$buscar%'
                                            AND ofer.estado_oferta = 1
                                            ORDER BY ofer.fecha_oferta DESC
                                            LIMIT $desde, $limiteConsulta");


    // consulta sin limitaciones para saber la cantidad de ofertas
    $queryTotalOfertas = mysqli_query($conn, "SELECT ofer.id_oferta_trabajo
                                            FROM usuario_empresa as usuEm
                                            LEFT JOIN oferta_trabajo as ofer
                                            ON usuEm.id_usuario_empresa = ofer.fk_id_usuario_empresa
                                            WHERE usuEm.id_usuario_empresa = '$id_empresa' 
                                            AND ofer.puesto LIKE '%$buscar%'
                                            AND ofer.estado_oferta = 1
                                            ORDER BY ofer.id_oferta_trabajo");
}


// OFERTAS CON ELIMINADAS
if (isset($_REQUEST['ofertasEliminadas'])) {

    $queryDatosOfertas = mysqli_query($conn, "SELECT id_oferta_trabajo, 
    puesto,
    detalle, 
    fecha_oferta 
    FROM oferta_trabajo 
    WHERE estado_oferta = 0
    AND fk_id_usuario_empresa = $id_empresa
    ORDER BY fecha_oferta DESC
    LIMIT $desde,$limiteConsulta");

    while (mysqli_next_result($conn)) {;
    }


    // numero total de las ofertas con aspirantes
    $queryTotalOfertas = mysqli_query($conn, "SELECT id_oferta_trabajo, 
    puesto,
    detalle, 
    fecha_oferta 
    FROM oferta_trabajo 
    WHERE estado_oferta = 0
    AND fk_id_usuario_empresa = $id_empresa");
}


// OFERTAS APROBADAS
if (isset($_REQUEST['puestosAprobados']) && $_REQUEST['puestosAprobados'] === 'ok') {

    $queryDatosOfertas = mysqli_query($conn, "call ofertasAprobadasDeEmpresas('$id_empresa', $desde, $limiteConsulta)");
    while (mysqli_next_result($conn)) {;
    }

    // total de ofertas aprobadas
    $queryTotalOfertas = mysqli_query($conn, "SELECT post.id_postula, oft.id_oferta_trabajo, oft.puesto, oft.detalle FROM oferta_trabajo oft
                                                LEFT JOIN usuario_empresa as usuEm 
                                                ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa
                                                LEFT JOIN postula post
                                                ON oft.id_oferta_trabajo = post.fk_id_oferta_trabajo
                                                WHERE usuEm.id_usuario_empresa =  '$id_empresa'
                                                AND post.aprobado = 1
                                                
                                                ");
}


// datos para la paginacion
$totalOfertas = mysqli_num_rows($queryTotalOfertas);
$total_paginas = ceil($totalOfertas / $limiteConsulta);




//Funcion para limintar una cadena de texto
function limitar_cadena($cadena, $limite, $sufijo)
{

    // Si la longitud es mayor que el límite...
    if (strlen($cadena) > $limite) {
        // Entonces corta la cadena y ponle el sufijo
        return substr($cadena, 0, $limite) . $sufijo;
    }

    // Si no, entonces devuelve la cadena normal
    return $cadena;
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../imagenes/Iconos/iconoAdmin/iconoPaginas.gif">

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

    <link rel="stylesheet" href="estiloTodasLasOfertas.css">
    <title>Mostrar Todas las ofertas</title>
</head>

<body>

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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../INICIOEMPRESA/inicioEmpresa.php"><img src="../../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href=""><img src="../../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="#"><img src="../../imagenes/Iconos/campana.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../../cerrarSesion.php"><img src="../../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav">
                            <a class="nav-link enlace-avatar" aria-current="page" href="../perfilEmpresa.php"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerDatosEmpresa['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>



    <main class="main">

        <section class="contenedorOferta container">

            <!-- TITULO Y FORMULARIO -->
            <div class="contenedorTituloYBuscador">

                <h1>Todas las ofertas <?php if (isset($_REQUEST['ofertasEliminadas'])) echo "'Eliminadas'"  ?> (<?php echo $totalOfertas ?>)</h1>

                <!-- BUSCAR -->
                <form action="" method="get" class="formulario">

                    <div class="input-group">

                        <div class="form-outline">
                            <input type="search" name="buscar" placeholder="Buscar por nombre" id="form1" class="form-control" required />

                        </div>

                        <button type="submit" class="btn ">
                            <i class="fas fa-search"></i>
                        </button>

                    </div>

                </form>


            </div>

            <!-- CONTENEDOR CARTA -->
            <div class="contendorCartas">

                <?php
                while ($recorrerOfertas = mysqli_fetch_array($queryDatosOfertas)) {
                ?>
                    <div class="carta">
                        <h2><?php echo $recorrerOfertas['puesto'] ?></h2>
                        <p> <?php echo limitar_cadena($recorrerOfertas['detalle'], 140, '...') ?></p>
                        <a onclick="irOferta(<?php echo $recorrerOfertas['id_oferta_trabajo'] ?>)" class="verDetalles" style="cursor: pointer;">Ver detalles...</a>


                        <?php
                        // solo mustra el editar oferta cuando la oferta no esta eliminada
                        if (empty($_REQUEST['ofertasEliminadas'])) {
                            if (empty($_REQUEST['puestosAprobados'])) {
                        ?>
                                <a class="editar" href="../INGRESAROFERTA/ingresarOferta.php?editar=ok&id_oferta=<?php echo $recorrerOfertas['id_oferta_trabajo'] ?>">Editar...</a>

                        <?php
                            }
                        }
                        ?>

                    </div>
                <?php
                }
                ?>



                <!-- si esta en la seccoin de ver las ofertas eliminadas, se muestra un link para ir a las ofertas activas y viceversa -->
                <?php
                if (!isset($_REQUEST['ofertasEliminadas'])) {
                ?>
                    <a href="?ofertasEliminadas=ok" class="mostrarOfertasConAspirante">Mostrar Ofertas eliminadas...</a>
                <?php
                } else { ?>
                    <a href="./mostrarTodasOferta.php" class="mostrarOfertasConAspirante">Mostrar Ofertas activas...</a>
                <?php
                }
                ?>


            </div>

            <!-- PAGINACION -->
            <nav class="paginacion" aria-label="Page navigation example">
                <ul class="pagination">
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
                            <li class="page-item <?php if ($pagina == $i + 1) echo 'active' ?>">
                                <a class="page-link" href="?pagina=<?php echo $i + 1;
                                                                    if (isset($_REQUEST['buscar'])) echo '&buscar=' . $_REQUEST['buscar'];
                                                                    if (isset($_REQUEST['ofertasEliminadas'])) echo '&ofertasEliminadas=ok';
                                                                    if (isset($_REQUEST['puestosAprobados'])) echo '&ofertasEliminadas=' . $_REQUEST['puestosAprobados'] ?>"> <?php echo $i + 1 ?> </a>
                            </li>
                        <?php
                        }
                    }

                    // para poner la limitacion de la paginacion (...)
                    if ($limitacion) {
                        ?>
                        <li class="page-item"><a class="page-link disabled">...</a></li>
                    <?php
                    }

                    ?>


                    <!-- boton seguir pagina -->
                    <li>
                        <a class="page-link <?php if ($pagina > $i - 1) echo 'disabled' ?>" href="?pagina=<?php echo $pagina + 1;
                                                                                                            if (isset($_REQUEST['buscar'])) echo '&buscar=' . $_REQUEST['buscar'];
                                                                                                            if (isset($_REQUEST['ofertasEliminadas'])) echo '&ofertasEliminadas=ok';
                                                                                                            if (isset($_REQUEST['puestosAprobados'])) echo '&ofertasEliminadas=' . $_REQUEST['puestosAprobados']
                                                                                                            ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </section>

    </main>

    <!-- SCRIPT BOOSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>


    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <!-- VALIDAR FORMULARIOS -->
    <script src="../../evitarReenvioFormulario.js"></script>


    <script>
        AOS.init();

        // ir a el detalle de la oferta
        const irOferta = id => {

            FD_ir = new FormData()
            FD_ir.append('id_oferta', id)



            fetch('../../queryIrOferta.php', {
                    method: 'POST',
                    body: FD_ir
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = '../../VEROFERTACONASPIRANTES/verOfertasConAspirantes.php'
                    }

                })
        }
    </script>



</body>

</html>