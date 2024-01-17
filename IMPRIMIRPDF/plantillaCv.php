<?php

session_start();

// si intenta entrar por url
if (!isset($_SESSION['id_aspirante'])) {
    header('Location: ../LOGIN/login.php');
    die();
}


include('../conexion.php');
include('../funciones.php');

$id_aspirante = $_SESSION['id_aspirante'];


// dato aspirante
$queryDatoAspirante = mysqli_query($conn, "call datosMainEstudiante('$id_aspirante')");
$rowAspirante = mysqli_fetch_assoc($queryDatoAspirante);
while (mysqli_next_result($conn)) {;
}


$id_cv = $rowAspirante['id_curriculum'];


// idiomas 
$queryIdioma = mysqli_query($conn, "SELECT * FROM idioma WHERE fk_id_curriculum = $id_cv ORDER BY id_idioma DESC LIMIT 3");

// referencias
$queryReferencia = mysqli_query($conn, "SELECT * FROM referencia WHERE fk_id_curriculum	= $id_cv ORDER BY id_referencia DESC LIMIT 3");

// conocimiento
$queryConocimiento = mysqli_query($conn, "SELECT * FROM conocimientos WHERE fk_id_curriculum	= $id_cv ORDER BY id_conocimientos DESC LIMIT 7");

// experiencia
$queryExperiencia = mysqli_query($conn, "SELECT * FROM experiencia WHERE fk_id_curriculum	= $id_cv ORDER BY id_experiencia  DESC LIMIT 3");

// educacion
$queryEducacion = mysqli_query($conn, "SELECT * FROM educacion WHERE fk_id_curriculum	= $id_cv ORDER BY id_educacion  DESC LIMIT 5");


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" href="../imagenes/iconos/iconoAdmin/iconoPaginas.gif">


    <link rel="stylesheet" href="platillaCv.css">
    <title>Cv <?php echo $rowAspirante['nombre'], $rowAspirante['apellido'] ?></title>
</head>

<body>

    <main>

        <div class="portafolio">
            <img src="../imagenes/Iconos/iconosCv/paginaWeb.jpg" width="30px">
            <?php echo $rowAspirante['portafolio'] ?>
        </div>

        <!-- IZQUIERDA -->
        <div class="contenedorIzquierda">

            <!-- logo -->
            <div class="contenedorLogo">
                <img src="../imagenes/logoUnesum.webp" alt="">
            </div>


            <!-- avatar -->
            <div class="contendorAvatar">
                <img src="<?php echo 'data:Image/jpeg;base64,' . base64_encode($rowAspirante['imagen_perfil']) ?>" alt="Imagen perfil">
            </div>


            <!-- contacto -->
            <div class="contendorContacto">
                <ul>
                    <li>
                        <img src="../imagenes/Iconos/iconosCv/telefono.png" width="25px">
                        <?php echo $rowAspirante['numero_celular'] ?>
                    </li>

                    <li>
                        <img src="../imagenes/Iconos/iconosCv/correo.webp" width="25px">
                        <?php echo $rowAspirante['correo'] ?>
                    </li>

                    <li>
                        <img src="../imagenes/Iconos/iconosCv/maps.webp" width="20px">
                        <?php echo $rowAspirante['lugar_donde_vive'] ?>
                    </li>

                    <li>
                        <img src="../imagenes/Iconos/iconosCv/persona.png" width="25px">
                        <?php echo calcularEdad($rowAspirante['fecha_nacimiento']) ?> años
                    </li>

                </ul>
            </div>

            <!-- idioma -->
            <div class="contenedorIdioma">

                <h5>IDIOMAS</h5>

                <ul>
                    <li>Español: <i>Nativo</i></li>

                    <?php

                    while ($rowIdioma = mysqli_fetch_assoc($queryIdioma)) {

                    ?>
                        <li><?php echo $rowIdioma['idioma'] ?>: <i><?php echo $rowIdioma['nivel'] ?></i></li>
                    <?php

                    }
                    ?>

                </ul>
            </div>

            <!-- referencias -->
            <div class="contenedorReferencias">

                <h5>REFERENCIAS</h5>

                <div class="subContenedorReferencia">


                    <?php

                    while ($rowReferencia = mysqli_fetch_assoc($queryReferencia)) {

                    ?>

                        <div class="cartaReferencia">
                            <p><?php echo $rowReferencia['nombre_referente'] ?></p>
                            <p class="ocupacionReferencia"><?php echo $rowReferencia['cargo_referente'] ?></p>
                            <p><?php echo $rowReferencia['numero_celular'] ?></p>
                            <p><?php echo $rowReferencia['correo_referente'] ?></p>
                        </div>
                        <hr>

                    <?php

                    }
                    ?>

                </div>
            </div>

        </div>


        <!-- DERECHA -->
        <div class="contendorDerecha">

            <h2><?php echo $rowAspirante['nombre'], $rowAspirante['apellido'] ?></h2>
            <h5><?php echo $rowAspirante['especializacion_curriculum'] ?> <span class="estado">(<?php echo $rowAspirante['estado_trabajo'] ?>)</span></h5>


            <!-- detalle personal -->
            <div class="contenedorDetalle">
                <p>
                    <?php echo $rowAspirante['detalle_curriculum'] ?>
                </p>
            </div>


            <!-- conocimientos -->
            <div class="contendorConocimiento">

                <h4>Conocimientos</h4>

                <div class="contenedorCartas">

                    <?php

                    while ($rowConocimiento = mysqli_fetch_assoc($queryConocimiento)) {
                    ?>

                        <div class="carta"><?php echo $rowConocimiento['nombre_conocimiento'] ?></div>

                    <?php
                    }

                    ?>


                </div>

            </div>


            <!-- experiencia -->
            <div class="contenedorExperiencia">
                <h4>Experiencia Profesional</h4>

                <div class="subContenedorExperiencia">

                    <?php
                    while ($rowExperiencia = mysqli_fetch_assoc($queryExperiencia)) {
                    ?>

                        <div class="experienciaProfecional">
                            <span class="ocupacion"><b><?php echo $rowExperiencia['cargo'] ?></b></span><br>
                            <span class="establecimiento">Establecimiento: <?php echo $rowExperiencia['nombre_empresa'] ?></span><br>
                            <span class="años"><?php echo $rowExperiencia['tiempo_trabajo'] ?> </span>
                            <p class="años">Tareas realizadas: <?php echo $rowExperiencia['tareas_realizadas'] ?></p>
                        </div>

                    <?php
                    }
                    ?>


                </div>


            </div>

            <hr>

            <!-- educacion -->
            <div class="contenedorEducacion">

                <h4>Educación</h4>

                <div class="subContenedorEducacion">


                    <?php
                    while ($rowEducacion = mysqli_fetch_assoc($queryEducacion)) {
                    ?>

                        <div class="educacionProfecional">
                            <span class="ocupacion"><b><?php echo $rowEducacion['nombre_institucion'] ?> (<?php echo $rowEducacion['tipo'] ?>)</b></span><br>
                            <span class="especialidad">Especialidad: <?php echo $rowEducacion['especializacion'] ?></span><br>
                            <span class="años">Culminación: <?php echo $rowEducacion['fecha_culminacion'] ?></span>
                        </div>

                    <?php
                    }
                    ?>




                </div>
            </div>
        </div>


    </main>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


    <script>
        window.print()
    </script>
</body>

</html>