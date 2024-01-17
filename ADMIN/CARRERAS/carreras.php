<?php
session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../index.html');
    die();
}

include("../../conexion.php");


// GUARDAR LAS CARRERAS
if (isset($_POST['guardarCarrera'])) {


    // VALIDAR QUE LOS DATOS NO VENGAN VACIOS
    if (
        !isset($_POST['categoriaCarrea']) ||
        $_POST['categoriaCarrea'] == "" ||
        !isset($_POST['nombreCarrera']) ||
        $_POST['nombreCarrera'] == ""
    ) {

        echo "<script> alert('Datos vacios') </script>";
        echo "<script> window.history.back() </script>";
        die();
    }

    $categoriaCarrea = $_POST['categoriaCarrea'];
    $nombreCarrera = $_POST['nombreCarrera'];
    $tituloCarrera = $_POST['tituloCarrera'];


    $queryIngresasrCarrera = mysqli_query($conn, "INSERT INTO carreras (categoria, nombre_carrera,tituloGraduado) VALUES ('$categoriaCarrea', '$nombreCarrera','$tituloCarrera')");

    if ($queryIngresasrCarrera) {
?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="../modalCorrecto.js"></script>

        </body>

    <?php
    }
}


////////////////////////////    CONSULTAR LAS CARRERAS      ////////////////////////////

//ciencias tegnicas
$queryCienciasTecnicas = mysqli_query($conn, "call consultaCarreras('Ciencias Tecnicas')");
while (mysqli_next_result($conn)) {;
}

// ciencias de la salud
$queryCienciasDeLaSalud = mysqli_query($conn, "call consultaCarreras('Ciencias en la Salud')");
while (mysqli_next_result($conn)) {;
}

// ciencias Economicas
$queryCienciasEconomicas = mysqli_query($conn, "call consultaCarreras('Ciencias Economicas')");
while (mysqli_next_result($conn)) {;
}

// ciencias Naturales y de la Agricultura
$queryCienciasNaturalesAgricultura = mysqli_query($conn, "call consultaCarreras('Ciencias Naturales y de la Agricultura')");
while (mysqli_next_result($conn)) {;
}



////////////////////////////    BORRAR      ////////////////////////////

if (isset($_REQUEST['id_borrar'])) {

    $codigo_carrera = $_REQUEST['id_borrar'];

    // consulta eliminar
    $queryEliminar = mysqli_query($conn, "UPDATE `carreras` SET `estado` = '0' WHERE `id_carrera` = '$codigo_carrera' ");

    if ($queryEliminar) {
    ?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalEliminarCorrecto.js"></script>

        </body>

    <?php
        die();
    }
} else if (isset($_REQUEST['id_activar'])) {

    $codigo_carrera = $_REQUEST['id_activar'];

    // consulta eliminar
    $queryEliminar = mysqli_query($conn, "UPDATE `carreras` SET `estado` = '1' WHERE `id_carrera` = '$codigo_carrera' ");

    if ($queryEliminar) {
    ?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalEliminarCorrecto.js"></script>

        </body>

<?php
        die();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../imagenes/iconos/iconoAdmin/kitty.gif">

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

    <link rel="stylesheet" href="./estiloCarrera.css">
    <link rel="stylesheet" href="../estiloHeader.css">
    <title>Carreras</title>
</head>

<body>


    <header class="header">
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../../index.html">
                    <img src="../../imagenes/logoUnesum.png" alt="">
                </a> -->

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav ">

                        <div class="dropdown ">
                            <div class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Extras
                            </div>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../EXTRAS/TIPOOFERTAEMPLEO/tipoOferta.php">Tipo Oferta Empleo</a></li>
                                <li><a class="dropdown-item" href="../EXTRAS/TIPOLUGAREMPLEO/tipoLugarOferta.php">Tipo Lugar Oferta Empleo</a></li>
                                <li><a class="dropdown-item" href="../EXTRAS/TIPOHORARIO/tipoHorario.php">Tipo Horario Empleo</a></li>
                                <li><a class="dropdown-item" href="../EXTRAS/PUBLICIDAD/publicidad.php">Publicidad</a></li>
                            </ul>
                        </div>


                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../admin.php">Admin</a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../CEDULAS/cedulas.php">Cedulas</a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace activo" aria-current="page">Carreras</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../EDICIONASPIRANTE/edicionAspirante.php">Edicion Aspirante</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../EDICIONEMPRESA/edicionEmpresa.php">Edicion Empresa</a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../ESTADISTICAS/estadisticas.php">Estadisticas</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../../cerrarSesion.php"><img src="../../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                    </ul>

                </div>


            </div>

        </nav>
    </header>



    <main class="main">

        <!-- CARRERAS EN LA BOLSA -->
        <section class="seccionCarreras" data-aos="fade-right">

            <div class="titulo">
                <h1>Carreras en la Bolsa</h1>
                <hr>
            </div>

            <div class="conenedorCarreras">

                <!-- CIENCIAS TEGNICAS -->
                <div class="carrerasTecnicas carreras">

                    <h2>Ciencias Tecnicas</h2>

                    <ul>
                        <?php
                        while ($recorrerCarreraTecnicas = mysqli_fetch_array($queryCienciasTecnicas)) {
                            $id_carrera = $recorrerCarreraTecnicas['id_carrera'];
                        ?>

                            <!-- muestra el nombre de la carrera -->
                            <li <?php if ($recorrerCarreraTecnicas['estado'] == 0) echo 'style="color: red;"' ?>>

                                <!--nombre  -->
                                <?php echo $recorrerCarreraTecnicas['nombre_carrera'], "\r\n" ?>

                                <!-- boton accion -->
                                <?php

                                if ($recorrerCarreraTecnicas['estado'] != 0) {

                                ?>
                                    <a onclick="confirmacion(event)" href="?id_borrar=<?php echo $id_carrera ?>"> X </a>
                                <?php

                                } else {

                                ?>
                                    <a onclick="confirmacion(event)" href="?id_activar=<?php echo $id_carrera ?>"> ✔️ </a>
                                <?php

                                }


                                ?>
                            </li>
                        <?php
                        }

                        ?>

                    </ul>
                </div>


                <!-- CIENCIAS DE LA SALUD -->
                <div class="cienciasSalud carreras">

                    <h2>Ciencias en la Salud</h2>

                    <ul>
                        <?php
                        while ($recorrerCienciasDeLaSalud = mysqli_fetch_array($queryCienciasDeLaSalud)) {

                            $id_carrera = $recorrerCienciasDeLaSalud['id_carrera'];
                        ?>
                            <li <?php if ($recorrerCienciasDeLaSalud['estado'] == 0) echo 'style="color: red;"' ?>>

                                <!--nombre  -->
                                <?php echo $recorrerCienciasDeLaSalud['nombre_carrera'], "\r\n" ?>

                                <!-- boton accion -->
                                <?php

                                if ($recorrerCienciasDeLaSalud['estado'] != 0) {

                                ?>
                                    <a onclick="confirmacion(event)" href="?id_borrar=<?php echo $id_carrera ?>"> X </a>
                                <?php

                                } else {

                                ?>
                                    <a onclick="confirmacion(event)" href="?id_activar=<?php echo $id_carrera ?>"> ✔️ </a>
                                <?php

                                }


                                ?>
                            </li>
                        <?php
                        }

                        ?>
                    </ul>
                </div>

                <!-- CIENCIAS ECONOMICAS -->
                <div class="cienciasEconomicas carreras">

                    <h2>Ciencias Economicas</h2>

                    <ul>
                        <?php
                        while ($recorrerCienciasEconomicas = mysqli_fetch_array($queryCienciasEconomicas)) {
                            $id_carrera = $recorrerCienciasEconomicas['id_carrera'];
                        ?>
                            <li <?php if ($recorrerCienciasEconomicas['estado'] == 0) echo 'style="color: red;"' ?>>

                                <!--nombre  -->
                                <?php echo $recorrerCienciasEconomicas['nombre_carrera'], "\r\n" ?>

                                <!-- boton accion -->
                                <?php

                                if ($recorrerCienciasEconomicas['estado'] != 0) {

                                ?>
                                    <a onclick="confirmacion(event)" href="?id_borrar=<?php echo $id_carrera ?>"> X </a>
                                <?php

                                } else {

                                ?>
                                    <a onclick="confirmacion(event)" href="?id_activar=<?php echo $id_carrera ?>"> ✔️ </a>
                                <?php

                                }


                                ?>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>

                </div>

                <!-- CIENCIAS NATURALES DE LA AGRICULTURA -->
                <div class="CienciasNaturalesDeAgricultura carreras">

                    <h2>Ciencias Naturales y de la Agricultura</h2>

                    <ul>
                        <?php
                        while ($recorrerCienciasNaturalesAgricultura = mysqli_fetch_array($queryCienciasNaturalesAgricultura)) {
                            $id_carrera = $recorrerCienciasNaturalesAgricultura['id_carrera'];
                        ?>
                            <li <?php if ($recorrerCienciasNaturalesAgricultura['estado'] == 0) echo 'style="color: red;"' ?>>

                                <!--nombre  -->
                                <?php echo $recorrerCienciasNaturalesAgricultura['nombre_carrera'], "\r\n" ?>

                                <!-- boton accion -->
                                <?php

                                if ($recorrerCienciasNaturalesAgricultura['estado'] != 0) {

                                ?>
                                    <a onclick="confirmacion(event)" href="?id_borrar=<?php echo $id_carrera ?>"> X </a>
                                <?php

                                } else {

                                ?>
                                    <a onclick="confirmacion(event)" href="?id_activar=<?php echo $id_carrera ?>"> ✔️ </a>
                                <?php

                                }


                                ?>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>


            </div>

        </section>


        <!-- FORMULARIO -->
        <section class="seccionFormulario" data-aos="fade-left">

            <form action="" method="post" class="formulario">

                <div class="titulo">
                    <h3>Agregar Carrera</h3>
                    <hr>
                </div>

                <!-- CATEGORIA CARRERA -->
                <div class="seleccion mb-3">
                    <select class="form-select" name="categoriaCarrea" aria-label="Default select example" required>
                        <option selected value="" disabled>Categoria de la carrera*</option>
                        <option value="Ciencias Tecnicas">Ciencias Tecnicas</option>
                        <option value="Ciencias en la Salud">Ciencias en la Salud</option>
                        <option value="Ciencias Economicas">Ciencias Economicas</option>
                        <option value="Ciencias Naturales y de la Agricultura">Ciencias Naturales y de la Agricultura</option>
                    </select>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="nombreCarrera" class="form-control" id="floatingInput" placeholder="Nombre de la carrera" required>
                    <label for="floatingInput">Nombre de la carrera*</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="tituloCarrera" class="form-control" id="tituloCarrera" placeholder="Titulo de la carrera" required>
                    <label for="tituloCarrera">Titulo de la carrera*</label>
                </div>

                <div class="form mb-3">

                    <input type="submit" name="guardarCarrera" class="form-control boton" id="floatingInput" value="Guardar">

                </div>

            </form>


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
    <script>
        AOS.init();
    </script>


    <!-- confirmar Eliminacion -->
    <script src="./confirmacionEliminar.js"></script>


</body>

</html>