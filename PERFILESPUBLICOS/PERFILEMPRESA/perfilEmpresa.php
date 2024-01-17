<?php
session_start();


if (!isset($_SESSION['id_empresa'])) {
    header('Location: ../../LOGIN/login.php');
    die();
}

include('../../conexion.php');
include('../../funciones.php');

if (!empty($_REQUEST['id_empresa'])) {
    $id_empresa = $_REQUEST['id_empresa'];
} else {
    $id_empresa = $_SESSION['id_empresa'];
}



// query para poner la foto de perfil del apirante en el header
$id_aspirante = $_SESSION['id_aspirantes'];
$queryFotoAspirante = mysqli_query($conn, "SELECT imagen_perfil FROM usuario_estudiantes usuEs LEFT JOIN datos_estudiantes dt ON usuEs.id_UsuEstudiantes = dt.fk_id_UsuEstudiantes WHERE usuEs.id_UsuEstudiantes = '$id_aspirante' ");
$recorrerFotoAspirante = mysqli_fetch_array($queryFotoAspirante);


//mostrar todos los datos de la empresa
$queryDatosEmpresa = "SELECT * FROM datos_empresa WHERE fk_id_usuario_empresa = '$id_empresa' ";
$respuestaDatosEmpresa = mysqli_query($conn, $queryDatosEmpresa);

$recorrerDatosEmpresa = mysqli_fetch_array($respuestaDatosEmpresa);



//MOSTRAR LAS OFERTAS QUE TIENE LA EMPRESA
$queryOfertaEmpresa = "SELECT * FROM oferta_trabajo WHERE fk_id_usuario_empresa = '$id_empresa' AND estado_oferta = 1 ORDER BY fecha_oferta DESC LIMIT 5 ";
$resultadoOfertaEmpresa = mysqli_query($conn, $queryOfertaEmpresa);


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


    <link rel="stylesheet" href="../../PERFILEMPRESA/estiloPerfilEmpresa.css">
    <link rel="stylesheet" href="./estiloEmpresa.css">
    <title>Empresa</title>
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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../PERFILASPIRANTE/INICIO/inicio.php"><img src="../../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../ENCONTRETRABAJO/encontreTrabajo.php"><img src="../../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../../cerrarSesion.php"><img src="../../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav" style="width: 50px; height: 50px;">
                            <a class="nav-link enlace-avatar" aria-current="page" href="../../PERFILASPIRANTE/perfilAspirante.php" style="padding: 0;"><img style="width: 100%; height: 100%; object-fit: cover;" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerFotoAspirante['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="main">

        <section class="seccionPerfil">

            <!-- PORTADA Y AVATAR -->
            <div class="contenedorPortada">
                <img src="../../imagenes/portadaDefectoPerfil.jpg" alt="">

                <div class="contendorFotoPerfil" style="cursor: pointer;">
                    <img onclick="verAvar()" id="imagenPerfil" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerDatosEmpresa['imagen_perfil']) ?>" alt="">
                </div>
            </div>

            <!-- DATOS -->
            <div class="datosPerfil mt-5 mb-4">
                <div class="informacionPerfil">
                    <h3><?php echo $recorrerDatosEmpresa['nombreUsuario'] ?></h3>
                    <span>Contacto: <?php echo $recorrerDatosEmpresa['correo'] ?></span> <br>
                    <span><?php echo $recorrerDatosEmpresa['lugar'] ?></span>
                </div>
            </div>

            <hr class="hr">

            <!-- PUESTOS OFERTADOS -->
            <div class="puestos_ofertados">

                <h3>Puestos Ofertados</h3>

                <!-- OFERTAS DE LA EMPRESA -->
                <div class="contenedorOfertas">

                    <?php

                    while ($recorrerOfertaEmpresa = mysqli_fetch_array($resultadoOfertaEmpresa)) {
                    ?>
                        <div class="ofertaCarta">
                            <h4><?php echo $recorrerOfertaEmpresa['puesto'] ?></h4>
                            <span><?php echo limitar_cadena($recorrerOfertaEmpresa['detalle'], 63, '...') ?></span><br><br>
                            <a onclick="irOferta(<?php echo $recorrerOfertaEmpresa['id_oferta_trabajo'] ?>, <?php echo $id_empresa ?>)" style="color: blue; text-decoration: underline; cursor: pointer;" class=" verDetalles">Ver detalles...</a>
                        </div>

                    <?php
                    }

                    ?>

                </div>
            </div>

        </section>


        <section class="seccionAcerdaDe">

            <div class="acerdaDe">

                <h3>Acerda de</h3>

                <hr>

                <p><?php echo limitar_cadena($recorrerDatosEmpresa['detalle_empresa'], 244, '...') ?></p>

            </div>

            <div class="personalEmpresa">
                <h3>Personal Empresa</h3>
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

                <h3><?php echo $recorrerDatosEmpresa['ofertas_aprobadas'] ?></h3>
            </div>

        </section>


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
                </div>

                <!-- SERVICIOS -->
                <div class="texto">
                    <p class="textoServicio"><b>Servicios que Ofrecemos</b></p>
                    <p class="parrafoServicio"><?php echo limitar_cadena($recorrerDatosEmpresa['servicios_ofrecer'], 264, '...') ?></p>
                </div>

                <!-- MAPS -->
                <div class="maps">
                    <?php echo $recorrerDatosEmpresa['lugarMaps'] ?>
                </div>

            </div>

        </section>

        <footer class="linkEmpresa">
            <span>Visita mi Pagina: </span>
            <a target="_blank" href="<?php echo $recorrerDatosEmpresa['pagina_web'] ?>"> <?php echo Limitar_cadena($recorrerDatosEmpresa['pagina_web'], 22, '...') ?> </a>
        </footer>
    </main>

    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <!-- script modal boostrap -->
    <script src="./modalBoostrap.js"></script>


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
                        window.location.href = `../../DETALLEOFERTA/detalleOferta.php?id_empresa=${id_empresa}`;
                    }

                })
        }

        //
    </script>


</body>

</html>