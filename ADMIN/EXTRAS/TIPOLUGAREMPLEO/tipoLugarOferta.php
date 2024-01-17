<?php

session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../../index.html');
    die();
}

include('../../../conexion.php');


// ELIMINAR
if (isset($_REQUEST['eliminar'])) {

    $id_eliminar = $_REQUEST['eliminar'];

    $queryEliminar = mysqli_query($conn, "DELETE FROM tipo_lugar_oferta WHERE id_tipo_lugar_oferta = '$id_eliminar' ");
    if ($queryEliminar > 0) {
        echo '<script>  
            alert("Eliminado")
            window.history.back()
        </script>';
    }
}

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


    <link rel="stylesheet" href="estiloLugarOferta.css">
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
                                <li><a class="dropdown-item" href="">Tipo Lugar Oferta Empleo</a></li>
                                <li><a class="dropdown-item" href="../TIPOHORARIO/tipoHorario.php">Tipo Horario Empleo</a></li>
                                <li><a class="dropdown-item" href="../PUBLICIDAD/publicidad.php">Publicidad</a></li>
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


    <!-- ICONO DEL SPIDERMAN -->
    <img class="iconoSpiderman" src="../../../imagenes/Iconos/iconoAdmin/spiderman.gif" width="100px" alt="">



    <main class="main">

        <!-- SE MUESTRA LOS DATOS -->
        <section class="seccionTipoOferta">

            <h1>Tipos Lugar ofertas</h1>
            <hr>

            <ul id="ul">
                <?php
                $queryDatosOferta = mysqli_query($conn, "SELECT * FROM tipo_lugar_oferta ORDER BY id_tipo_lugar_oferta DESC");

                while ($recorrerDatosOferta = mysqli_fetch_array($queryDatosOferta)) {
                ?>
                    <li><?php echo $recorrerDatosOferta['nombre'] ?> <a href="?eliminar=<?php echo $recorrerDatosOferta['id_tipo_lugar_oferta'] ?>">&times;</a></li>
                <?php
                }


                ?>

            </ul>

        </section>

        <!-- FORMULARIO -->
        <section class="seccionFormulario">


            <form action="" id="formulario">

                <h1>Ingresar Tipo Lugar Oferta</h1>
                <hr>

                <div class="mb-3">
                    <label for="nombreLugarOferta" class="form-label">Nombre del lugar oferta*</label>
                    <input type="text" class="form-control" id="nombreLugarOferta" name="nombreLugarOferta" placeholder="Nombre del lugar oferta" required>
                </div>

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
    <script src="../../../alertaPersonalizada.js"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>


    <script>
        var formulario = document.getElementById('formulario')
        var ul = document.getElementById('ul')

        // cuando se da submit en el formulario
        formulario.addEventListener('submit', function(e) {

            e.preventDefault()

            let formdata = new FormData(formulario)

            fetch('./ingresarLugarOferta.php', {
                    method: 'POST',
                    body: formdata
                })
                .then(res => res.json())
                .then(e => {

                    // datos vacios
                    if (e.mensaje == "Datos Vacios") {
                        alert(e.mensaje)
                    }

                    // si todo es correcto
                    if (e.mensaje == "DATOS INSERTADOS") {

                        alertaPersonalizada('CORRECTO', 'Insertado correctamente', 'success', 'Regresar', 'No')
                        ul.insertAdjacentHTML('afterbegin', `<li>${formdata.get('nombreLugarOferta')} <a href='?eliminar=${e.id}'> &times; </a> </li>`)


                        // limpiar el input
                        document.getElementById('nombreLugarOferta').value = ''

                    } else if (e.mensaje == "ERROR AL INSERTAR DATOS") {
                        alertaPersonalizada('INCORRECTO', 'Error al insertar datos', 'error', 'Regresar', 'No')
                        return
                    }
                })


        })
    </script>
</body>

</html>