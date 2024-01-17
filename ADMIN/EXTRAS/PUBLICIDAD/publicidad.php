<?php

session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../../index.html');
    die();
}

include('../../../conexion.php');


// MUSTRA LAS CARRERAS
$queryCarreras = mysqli_query($conn, "SELECT * FROM carreras WHERE estado = 1");


// MUSTRA LAS PUBICIDADES
$queryPublicidad = mysqli_query($conn, "SELECT publi.*,  car.nombre_carrera FROM publicidad  publi  
INNER JOIN carreras car 
ON car.id_carrera = publi.fk_id_carrera 
ORDER BY id_publicidad DESC");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../imagenes/iconos/iconoAdmin/kitty.gif">

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


    <!-- ALERTA PERSONALIZADA -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">


    <link rel="stylesheet" href="estiloPublicidad.css">
    <link rel="stylesheet" href="../../estiloHeader.css">
    <title>Lugar Oferta</title>
</head>

<body>

    <header class="header">

        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../../../index.html">
                    <img src="../../../imagenes/logoUnesum.png" alt="">
                </a> -->

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="contenedorNavbar collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav">

                        <div class="dropdown activo">
                            <div class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Extras
                            </div>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../TIPOOFERTAEMPLEO/tipoOferta.php">Tipo Oferta Empleo</a></li>
                                <li><a class="dropdown-item" href="../TIPOLUGAREMPLEO/tipoLugarOferta.php">Tipo Lugar Oferta Empleo</a></li>
                                <li><a class="dropdown-item" href="../TIPOHORARIO/tipoHorario.php">Tipo Horario Empleo</a></li>
                                <li><a class="dropdown-item" href="">Publicidad</a></li>
                            </ul>
                        </div>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../admin.php">Admin</a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../CARRERAS/carreras.php">Carreras</a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../CEDULAS/cedulas.php">Cedulas</a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../EDICIONASPIRANTE/edicionAspirante.php">Edicion Asp</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../EDICIONEMPRESA/edicionEmpresa.php">Edicion Emp</a>
                        </li>


                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../ESTADISTICAS/estadisticas.php">Estadisticas</a>
                        </li>




                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../../../cerrarSesion.php"><img src="../../../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                    </ul>

                </div>


            </div>

        </nav>

    </header>

    <main class="main">

        <!-- SE MUESTRA LOS DATOS DE LA PUBLICIDAD -->
        <section class="seccionTipoOferta">

            <h1>Publicidad</h1>
            <hr>

            <!-- CARTA PUBLICADAD -->
            <div class="contenedorPublicidad">

                <p id="detalleMostrar">

                </p>

                <a href="" class="link">Ir</a>


            </div>


            <hr>


            <h5>Publicidades Activas (<?php echo mysqli_num_rows($queryPublicidad) ?>)</h5>

            <?php

            while ($recorrerPublicidad = mysqli_fetch_assoc($queryPublicidad)) {
            ?>

                <div class="contenedorPublicidad mb-3">

                    <span class="cerrar" onclick="eliminar(<?php echo $recorrerPublicidad['id_publicidad'] ?>)">X</span>

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
                    <span class="carrera"> <b>Carrera:</b> <?php echo $recorrerPublicidad['nombre_carrera'] ?> </span>

                </div>

            <?php
            }
            ?>







        </section>

        <!-- FORMULARIO -->
        <section class="seccionFormulario">


            <form action="" id="formulario" class="">

                <h1>Ingresar Publicidad</h1>
                <hr>

                <!-- detalle -->
                <div class="mb-3">
                    <label for="detalleInput" class="form-label">Detalle de la publicidad*</label>
                    <textarea class="form-control" name="detalle" id="detalleInput" rows="5" required></textarea>
                </div>


                <!-- Link -->
                <div class="mb-3">
                    <label for="link" class="form-label">Link del Post <i>(opcional)</i> </label>
                    <input type="text" class="form-control" id="link" name="link" placeholder="Link">
                </div>


                <!-- Fecha de caducidad -->
                <div class="mb-3">
                    <label for="fechaCaducidad" class="form-label">Fecha de caducidad*</label>
                    <input type="date" class="form-control" id="fechaCaducidad" name="fechaCaducidad" placeholder="" required>
                </div>


                <!-- carrera dirigida -->
                <label for="carreraDirigida" class="form-label">Carrera Dirigida*</label>
                <select class="form-select mb-3" name="carreraDirigida" id="carreraDirigida" aria-label="Default select example" required>

                    <?php
                    while ($recorrerCarrera = mysqli_fetch_assoc($queryCarreras)) {
                    ?>
                        <option value="<?php echo $recorrerCarrera['id_carrera'] ?>"><?php echo $recorrerCarrera['nombre_carrera'] ?></option>

                    <?php
                    }

                    ?>

                </select>



                <div class="mb-3">
                    <input type="submit" class="form-control botonSubmit" id="botonSumit" name="BotonSubmit" value="Guardar">
                </div>

            </form>


        </section>

    </main>





    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- VALIDAR FORMULARIO VACIOS -->
    <script src="../../../LOGIN/scriptValidarFormulario.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="./alertaPersonalizadaNew.js"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>



    <script>
        AOS.init();


        const detalleMostrar = document.getElementById('detalleMostrar');
        const detalleInput = document.getElementById('detalleInput');
        const formulario = document.getElementById('formulario');


        detalleInput.addEventListener('input', function(e) {
            detalleMostrar.innerHTML = e.target.value
        })


        formulario.addEventListener('submit', function(e) {
            e.preventDefault();

            let FD = new FormData(formulario)

            fetch('queryInsertarPublicidad.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje !== 'ok') {
                        alertaPersonalizada('ERROR', 'Algo salio mal', 'error', 'Regresar', 'no')
                        return
                    }


                    if (e.mensaje === 'ok') {
                        alertaPersonalizada('CORRECTO', 'Publicidad Guardada', 'success', 'Regresar', 'si')

                    }
                })



        })


        const eliminar = id => {

            FD = new FormData();
            FD.append('id', id)

            fetch('queryEliminar.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {


                    if (e.mensaje !== 'ok') {
                        alertaPersonalizada('ERROR', 'Algo salio mal', 'error', 'Regresar', 'no')
                        return
                    }


                    if (e.mensaje === 'ok') {
                        alertaPersonalizada('CORRECTO', 'Publicidad Eliminada', 'success', 'Regresar', 'si')

                    }
                })
        }
    </script>



</body>

</html>