<?php
include("../../conexion.php");

session_start();
$mostrar_id = $_SESSION['id_aspirantes'];

//si se intenta ingresar sin iniciar sesion
if ($mostrar_id == null) {
    header('Location: ../../LOGIN/login.php');
    die();
}

// datos aspirante
$queryMainEstudiantes = "call datosMainEstudiante('$mostrar_id');";
$resultadoMainEstudiantes = mysqli_query($conn, $queryMainEstudiantes);
$recorrerMainEstudiantes = mysqli_fetch_array($resultadoMainEstudiantes);
while (mysqli_next_result($conn)) {;
}


//consultar datos de mis postulaciones
$queryPostulacion = "call consultaOfertaEmpleoEstudiante('$mostrar_id')";
$respuestaPostulacion = mysqli_query($conn, $queryPostulacion);
while (mysqli_next_result($conn)) {;
}



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


    <link rel="stylesheet" href="../INICIO/estiloInicio.css">
    <link rel="stylesheet" href="estiloMisPostulaciones.css">
    <title>Mis postulaciones</title>
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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../INICIO/inicio.php"><img src="../../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../../ENCONTRETRABAJO/encontreTrabajo.php"><img src="../../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>



                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../../cerrarSesion.php"><img src="../../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav">
                            <a class="nav-link enlace-avatar" aria-current="page" href="../perfilAspirante.php"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerMainEstudiantes['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="seccionMain">

        <section class="SeccionAprobados">

            <?php
            //mostrar las postulaciones aprobadas
            $queryPostulacionesAprobadas = "call postulacionAprobada('$mostrar_id') ";
            $respuestaPostulacionesAprobadas = mysqli_query($conn, $queryPostulacionesAprobadas);

            while ($recorrerPostulacionesAprobadas = mysqli_fetch_array($respuestaPostulacionesAprobadas)) {
            ?>
                <!-- carta -->
                <div class="carta">
                    <h5><?php echo $recorrerPostulacionesAprobadas['puesto'] ?> </h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus nobis rem culpa qui veniam illo aliquam cupiditate, quod sed provident?</p>
                    <li><b>Oferta: </b> <?php echo $recorrerPostulacionesAprobadas['tipo_empleo'] ?> </li>
                    <li><b>Tipo: </b> <?php echo $recorrerPostulacionesAprobadas['tipo_lugar_empleo'] ?> </li>
                    <li><b>Empresa: </b> </b> <?php echo $recorrerPostulacionesAprobadas['nombre'] ?> </li>
                    <span><?php echo $recorrerPostulacionesAprobadas['fecha_oferta'] ?></span>


                    <a href="../../DETALLEOFERTA/detalleOferta.php?id_oferta=<?php echo $recorrerPostulacionesAprobadas['fk_id_oferta_trabajo'] ?>&puesto=<?php echo urlencode($recorrerPostulacionesAprobadas['puesto']) ?>">Ver Detalles...</a>
                </div>
            <?php
            }
            ?>

        </section>




        <section class="SeccionMisPostulaciones">

            <?php
            while ($recorrerPostulaciones = mysqli_fetch_array($respuestaPostulacion)) {
            ?>

                <!-- carta -->
                <div class="carta">
                    <h5><?php echo $recorrerPostulaciones['puesto'] ?></h5>
                    <p><?php echo limitar_cadena($recorrerPostulaciones['detalle'], 138, '...') ?></p>
                    <li><b>Oferta: </b> <?php echo $recorrerPostulaciones['tipo_empleo'] ?> </li>
                    <li><b>Tipo: </b> <?php echo $recorrerPostulaciones['tipo_lugar_empleo'] ?> </li>
                    <li><b>Emresa: </b> <?php echo $recorrerPostulaciones['nombre'] ?> </li>
                    <span><?php echo $recorrerPostulaciones['fecha_oferta'] ?></span>


                    <a href="../../DETALLEOFERTA/detalleOferta.php?id_oferta=<?php echo $recorrerPostulaciones['id_oferta_trabajo'] ?>&puesto=<?php echo urlencode($recorrerPostulaciones['puesto']) ?>">Ver Detalles...</a>
                </div>


            <?php
            }


            ?>

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
    </script>

    <!-- evitar el reenvio de los formularios -->
    <script src="../../evitarReenvioFormulario.js"></script>


</body>

</html>