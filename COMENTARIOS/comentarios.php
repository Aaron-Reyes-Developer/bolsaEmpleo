<?php
session_start();

// error_reporting(0);

if (
    !isset($_SESSION['id_aspirantes']) &&
    !isset($_SESSION['id_empresa'])
) {
    header("Location: ../LOGIN/login.php");
    die();
}


include('../conexion.php');

// todo: cambiarlo con js

if (isset($_SESSION['id_aspirantes']) && !isset($_SESSION['id_empresa'])) {

    //mustra la foto del aspirante
    $id_aspirantes = $_SESSION['id_aspirantes'];
    $queryDatoPerfil = mysqli_query($conn, "SELECT imagen_perfil FROM datos_estudiantes WHERE fk_id_usuEstudiantes = '$id_aspirantes' ");


    // si se apreta el boton enviar
    if (isset($_POST['botonEnviar'])) {

        if ($_POST['comentario'] === "") {
            echo "<script> alert('Dato Vacio') </script>";
            die();
        }


        $comentario = $_POST['comentario'];
        $fecha = date('y-m-d');

        // comenario estudiante
        $queryIngresarComentario = mysqli_query($conn, "INSERT INTO comentarios (comentario, fecha,fk_id_usuEstudiantes) VALUES ('$comentario', '$fecha','$id_aspirantes') ");

        if ($queryIngresarComentario) {
?>

            <body>
                <!-- boostrap -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

                <!-- MODAL -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script src="./modalGuardadoCorrecto.js"></script>

            </body>
        <?php
        }
    }

    //
} else if (isset($_SESSION['id_empresa']) && !isset($_SESSION['id_aspirantes'])) {

    //mustra la foto de la empresa
    $id_empresa = $_SESSION['id_empresa'];
    $queryDatoPerfil = mysqli_query($conn, "SELECT imagen_perfil FROM datos_empresa    WHERE fk_id_usuario_empresa = '$id_empresa' ");



    // si se apreta el boton enviar
    if (isset($_POST['botonEnviar'])) {

        if ($_POST['comentario'] === "" || rtrim($_POST['comentario']) === " ") {
            echo "<script> alert('Dato Vacio') </script>";
            die();
        }


        $comentario = htmlspecialchars($_POST['comentario']);
        $fecha = date('y-m-d');

        // comentario empresa
        $queryIngresarComentario = mysqli_query($conn, "INSERT INTO comentarios (comentario, fecha, fk_id_empresa) VALUES ('$comentario', '$fecha', '$id_empresa')");


        if ($queryIngresarComentario) {
        ?>

            <body>
                <!-- boostrap -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

                <!-- MODAL -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script src="./modalGuardadoCorrecto.js"></script>

            </body>
<?php
        }
    }
}


// sacar la foto para el header
$recorrerImagenHeader = mysqli_fetch_array($queryDatoPerfil);




?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imagenes/iconos/iconoAdmin/iconoPaginas.gif">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="estiloComentraios.css">
    <title>Comentarios</title>
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


                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../cerrarSesion.php"><img src="../imagenes/Iconos/salir.svg" alt="Cerrar Sesion" title="Cerrar Sesion"></a>
                        </li>

                        <li class="nav-item lista-avatar-nav">
                            <?php

                            // se inicio sesion como aspirante
                            if (isset($_SESSION['id_aspirantes']) && !isset($_SESSION['id_empresa'])) {
                            ?>

                                <!-- ir a perfil aspirante -->
                                <a class="nav-link enlace-avatar" aria-current="page" href="../PERFILASPIRANTE/perfilAspirante.php"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerImagenHeader['imagen_perfil']) ?>" alt=""></a>

                            <?php
                            } else if (isset($_SESSION['id_empresa']) && !isset($_SESSION['id_aspirantes'])) {
                            ?>

                                <!-- ir a perfil empresa -->
                                <a class="nav-link enlace-avatar" aria-current="page" href="../PERFILEMPRESA/perfilEmpresa.php"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerImagenHeader['imagen_perfil']) ?>" alt=""></a>

                            <?php
                            }

                            ?>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="main" data-aos="fade-up">

        <div class="contenedorComentarios">

            <section class="seccionPortada">
                <img src="../imagenes/portadaComentarios.png" alt="">
            </section>

            <section class="seccionFormulario">

                <form action="" method="post" class="formulario">

                    <div class="conetenedorTitulo mb-3">
                        <h1>¡Bienvenidos a los comentarios! </h1>
                        <p>Nos encanta interactuar con nuestra comunidad y conocer sus opiniones, por eso queremos invitarlo a dejar su comentario y compartir sus experiencias con nosotros.</p>
                    </div>

                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Escribe un comentario sobre la plataforma*</label>
                        <textarea class="form-control" name="comentario" id="exampleFormControlTextarea1" rows="7" placeholder="Puedes decirnos tu experiencia en la plataforma, como podemos mejorar o algun error encontrado" required></textarea>
                    </div>

                    <div class="mb-3">
                        <input type="submit" value="Enviar" name="botonEnviar" class="botonEnviar btn btn-lite">
                    </div>
                </form>
            </section>

        </div>


    </main>

    <!-- animaciones -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <!-- eviar reenvio de formulario -->
    <script src="../evitarReenvioFormulario.js"></script>

    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>