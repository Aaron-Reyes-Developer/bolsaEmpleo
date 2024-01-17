<?php

include('../conexion.php');

session_start();
$id_aspirante = $_SESSION['id_aspirantes'];


//si se intenta ingresar sin iniciar sesion
if ($id_aspirante == null) {
    header('Location: ../LOGIN/login.php');
    die();
}

// apenas entra a la pagina se actualiza las notificaciones para que ya no se muestren en el header
$queryActualizarNotificacion = mysqli_query($conn, "UPDATE postula SET estado_noti = '0' WHERE fk_id_usuEstudiantes = '$id_aspirante' AND estado_noti = 1 ");
while (mysqli_next_result($conn)) {;
}

// foto para el header
$queryDatoAspirante = mysqli_query($conn, "SELECT usuEs.id_usuEstudiantes, dt.imagen_perfil FROM usuario_estudiantes as usuEs
                                            LEFT JOIN datos_estudiantes as dt
                                            ON usuEs.id_usuEstudiantes = dt.fk_id_usuEstudiantes
                                            WHERE  usuEs.id_usuEstudiantes = '$id_aspirante' ");

$recorrerDatoAspirante = mysqli_fetch_array($queryDatoAspirante);
while (mysqli_next_result($conn)) {;
}



// DATOS PARA LA PAGINACINON

$queryTotalNotificaciones = mysqli_query($conn, "call todasLasNotificacionesSinLimite('$id_aspirante')");
$TotalNotificaciones = mysqli_num_rows($queryTotalNotificaciones);
while (mysqli_next_result($conn)) {;
}


if (empty($_REQUEST['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_REQUEST['pagina'];
}

$limiteConsulta = 5;
$desde = ($pagina - 1) * $limiteConsulta;
$totalPaginas = ceil($TotalNotificaciones / $limiteConsulta);


// todas las notificaciones con limite de paginacion
$queryTodasLasNotificaciones = mysqli_query($conn, "call todasLasNotificaciones('$id_aspirante',$desde,$limiteConsulta)");
while (mysqli_next_result($conn)) {;
}
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

    <link rel="stylesheet" href="estiloVerNotificacion.css">
    <title>Notificaciones</title>
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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../COMENTARIOS/comentarios.php"><img src="../imagenes/Iconos/comentarios.png" alt="Comentarios" title="Comentarios"></a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../PERFILASPIRANTE/INICIO/inicio.php"><img src="../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../ENCONTRETRABAJO/encontreTrabajo.php"><img src="../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa iconoCampana">
                            <a class="nav-link iconoEnlace" aria-current="page"><img src="../imagenes/Iconos/campana.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>
                            <a class="nav-link iconoEnlace" aria-current="page" href="../cerrarSesion.php"><img src="../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav">
                            <a class="nav-link enlace-avatar" aria-current="page" href="#"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerDatoAspirante['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="container mt-3" data-aos="fade-up">

        <h1 class="display-6">Mis notificaciones (<?php echo $TotalNotificaciones ?>)</h1>

        <hr>


        <div class="contenedorTabla">
            <table class="table table-ligth table-striped">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Empresa</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Sueldo</th>
                        <th scope="col">Fecha <br> Aprobado</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $contador = 1;
                    while ($recorrerNotificacion = mysqli_fetch_array($queryTodasLasNotificaciones)) {
                    ?>

                        <tr>
                            <th scope="row"><?php echo $contador++ ?></th>


                            <!-- nombre de empresa -->
                            <td>
                                <img class="imagenPerfilTabla" src="data:Image/jpg;base64,<?php echo base64_encode($recorrerNotificacion['imagen_perfil']) ?>" alt="">
                                <?php
                                $_SESSION['id_empresa'] = $recorrerNotificacion['id_usuario_empresa'];
                                ?>
                                <a href="../PERFILESPUBLICOS/PERFILEMPRESA/perfilEmpresa.php?id_empresa=<?php echo $recorrerNotificacion['id_usuario_empresa'] ?>"><?php echo $recorrerNotificacion['nombre_empresa'] ?></a>
                            </td>


                            <!-- nombre puesto de trabajo -->
                            <td>
                                <div class="puntito"></div>

                                <a onclick="irOferta(<?php echo $recorrerNotificacion['id_oferta_trabajo'] ?>, <?php echo $recorrerNotificacion['id_usuario_empresa'] ?>)" style="color: blue; text-decoration: underline; cursor: pointer;">
                                    <?php echo $recorrerNotificacion['puesto'] ?>
                                </a>
                            </td>


                            <!-- tipo de puesto -->
                            <td>
                                <?php echo $recorrerNotificacion['tipo_oferta'] ?>
                            </td>


                            <!-- sueldo -->
                            <td>
                                <?php
                                if ($recorrerNotificacion['precio'] == 0) {
                                    echo "<div class='text-danger'> Privado </div>";
                                } else {
                                    echo $recorrerNotificacion['precio'];
                                }
                                ?>
                            </td>

                            <td><?php echo $recorrerNotificacion['fecha_aprobado'] ?></td>
                        </tr>

                    <?php
                    }
                    ?>




                </tbody>
            </table>
        </div>


        <!-- PAGINACION -->
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
                <a class="page-link <?php if ($i <= $pagina) echo 'disabled' ?>" href="?pagina=<?php echo $pagina + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
            </ul>
        </nav>

    </main>
    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();

        // ir a el detalle de la oferta
        const irOferta = (id_oferta, id_empresa) => {

            FD_ir = new FormData()
            FD_ir.append('id_oferta', id_oferta)



            fetch('../queryIrOferta.php', {
                    method: 'POST',
                    body: FD_ir
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = `../DETALLEOFERTA/detalleOferta.php?id_empresa=${id_empresa}`
                    }

                })
        }
    </script>

</body>

</html>