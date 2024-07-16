<?php

session_start();

if (isset($_SESSION['id_empresa']) == null || $_SESSION['id_empresa'] == "") {
    header("Location: ../../LOGIN/login.php");
}

include("../../conexion.php");
$id_empresa = $_SESSION['id_empresa'];


//mostrar todos los datos de la empresa
$queryDatosEmpresa = "SELECT * FROM datos_empresa WHERE fk_id_usuario_empresa = '$id_empresa' ";
$respuestaDatosEmpresa = mysqli_query($conn, $queryDatosEmpresa);
$recorrerDatosEmpresa = mysqli_fetch_array($respuestaDatosEmpresa);
while (mysqli_next_result($conn)) {;
}



//consulta para saber si existen postulantes en las ofertas de trabajo
$queryPostulantesEnOferta = "call consultaCuantosAspirantesEstanEnUnaOferta('$id_empresa',0,10)";
$respuestaPostulantesEnOferta = mysqli_query($conn, $queryPostulantesEnOferta);
while (mysqli_next_result($conn)) {;
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


    <link rel="stylesheet" href="estiloInicioEmpresa.css">
    <title>Inicio Empresa</title>
</head>

<body>

    <header class="">
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <a class="navbar-brand" href="../INICIOEMPRESA/inicioEmpresa.php">
                    <img src="../../imagenes/Iconos/iconoAdmin/iconoPaginas.gif" style="width: 50px;" alt="">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav">

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./inicioEmpresa.php"><img src="../../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="#"><img src="../../imagenes/Iconos/maleta.svg" alt=""></a>
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

        <section class="seccionPostulantes Postulantes">
            <h2>Postulantes</h2>
            <hr>

            <div class="contenedorPostulantes">

                <?php

                while ($recorrerPostulantesEnOferta = mysqli_fetch_array($respuestaPostulantesEnOferta)) {
                    $id_oferta = $recorrerPostulantesEnOferta['id_oferta_trabajo'];
                ?>

                    <!-- carta -->
                    <div class="postulante">
                        <h5><?php echo $recorrerPostulantesEnOferta['puesto'] ?></h5>
                        <a href="../../VEROFERTACONASPIRANTES/verOfertasConAspirantes.php?puesto=<?php echo urlencode($recorrerPostulantesEnOferta['puesto']) ?>">Ver aspirantes...</a>
                    </div>
                <?php
                }

                ?>




            </div>
        </section>





    </main>


    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>