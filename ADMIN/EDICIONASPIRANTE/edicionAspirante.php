<?php
session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../index.html');
    die();
}

include("../../conexion.php");

// BUSCAR ASPIRANTE
$queryBuscar = "";
if (isset($_POST['buscar'])) {

    if ($_POST['buscar'] == "datoBuscar") {
        echo "<script>  alert('Dato vacio')  </script>";
    }

    $datoBuscar = $_POST['datoBuscar'];
    $queryBuscar = mysqli_query($conn, "call adminConsultaAspirante('$datoBuscar')");
    while (mysqli_next_result($conn)) {;
    }
}

// REDIRECCIONAR A EL PERFIL ASPIRANTE PARA EDITAR
if (isset($_GET['id_aspirante'])) {

    $_SESSION['id_aspirantes'] = $_GET['id_aspirante'];
    header('Location: ../../PERFILASPIRANTE/perfilAspirante.php');
}

// ELIMINAR
if (isset($_REQUEST['id_eliminar'])) {

    if ($_REQUEST['id_eliminar'] == "") {
        echo "ID vacio";
        die();
    }

    $id_aspirante = $_REQUEST['id_eliminar'];

    $queryEliminar = mysqli_query($conn, "UPDATE usuario_estudiantes SET estado_cuenta = '0' WHERE id_usuEstudiantes = '$id_aspirante' ");

    if ($queryEliminar) {
        echo "<script>  alert('Usuario Eliminado/Ocultado') </script>";
        echo "<script>  window.location.href = './edicionAspirante.php' </script>";
    }
}


// OPERACION PAGINACION

// total de aspirantes
$queryListaAspiranteTotal = mysqli_query($conn, "SELECT usuEs.id_usuEstudiantes
                                                    FROM usuario_estudiantes as usuEs 
                                                    LEFT JOIN datos_estudiantes as datos
                                                    ON usuEs.id_usuEstudiantes = datos.fk_id_usuEstudiantes
                                                    WHERE usuEs.estado_cuenta = 1");

// datos paginacion
$totalAspirantes = mysqli_num_rows($queryListaAspiranteTotal);

$limiteConsulta = 5;

if (empty($_REQUEST['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_REQUEST['pagina'];
}

$desde = ($pagina - 1) * $limiteConsulta;
$totalPagina = ceil($totalAspirantes / $limiteConsulta);



// CONSULTA PARA MOSTRAR LA LISTA DE ASPIRANTE
$queryListaAspirante = mysqli_query($conn, "SELECT usuEs.id_usuEstudiantes, usuEs.id_usuEstudiantes , datos.cedula, datos.nombre, datos.apellido 
                                                    FROM usuario_estudiantes as usuEs 
                                                    LEFT JOIN datos_estudiantes as datos
                                                    ON usuEs.id_usuEstudiantes = datos.fk_id_usuEstudiantes
                                                    WHERE usuEs.estado_cuenta = 1
                                                    ORDER BY usuEs.id_usuEstudiantes DESC
                                                    LIMIT $desde, $limiteConsulta");

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

    <!-- ANIMACION LIBRERIA -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="estiloEdicionAspirante.css">
    <link rel="stylesheet" href="../estiloHeader.css">
    <title>Edicion Aspirante</title>
</head>

<body>


    <header class="header">

        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../index.html">
                    <img src="../../imagenes/logoUnesum.png" alt="">
                </a> -->

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="contenedorNavbar collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav">

                        <div class="dropdown">
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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../CARRERAS/carreras.php">Carreras</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace activo" aria-current="page">Edicion Aspirante</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../EDICIONEMPRESA/edicionEmpresa.php">Edicion Emp</a>
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

    <main class="main container">

        <!-- LISTA DE EL ASPIRANTE BUSCADO -->
        <?php
        if ($queryBuscar != "") {
            while ($recorrerDatosAspirante = mysqli_fetch_array($queryBuscar)) {
        ?>
                <div class="bg-dark ">
                    <div class="cartaAspirante text-light">

                        <span class="cedula"> <?php echo $recorrerDatosAspirante['cedula'] ?> </span>
                        <span class="nombre"> <?php echo $recorrerDatosAspirante['nombre'], " ", $recorrerDatosAspirante['apellido'] ?> </span>


                        <div class="acciones">

                            <span><a class="text-light" onClick="confirmacion(event)" href="#">X</a></span>

                            <span>
                                <a class="text-light" href="?id_aspirante=<?php echo $recorrerDatosAspirante['id_usuEstudiantes'] ?>">Editar</a>
                            </span>

                        </div>
                    </div>

                </div>
        <?php
            }
        }
        ?>


        <!-- MOSTRAR USUARIOS -->
        <section class="mb-3">

            <h1>Ultimos registros de Aspirantes (<?php echo $totalAspirantes ?>) </h1>
            <a class="mostrarCuentasEliminadas" href="./cuentasEliminadas.php">Mostrar Cuentas eliminadas</a>

            <!-- LISTA USUARIOS -->
            <div class="contenedorTodosAspirantes">

                <?php
                while ($recorrerListaAspirante = mysqli_fetch_array($queryListaAspirante)) {
                ?>
                    <div>
                        <div class="cartaAspirante">
                            <span class="cedula"><?php echo $recorrerListaAspirante['cedula'] ?></span>
                            <span class="nombre"><?php echo $recorrerListaAspirante['nombre'], " ", $recorrerListaAspirante['apellido'] ?></span>

                            <div class="acciones">
                                <span><a onClick="confirmacion(event)" href="?id_eliminar=<?php echo $recorrerListaAspirante['id_usuEstudiantes'] ?>">X</a></span>
                                <span><a href="?id_aspirante=<?php echo $recorrerListaAspirante['id_usuEstudiantes'] ?>">Editar</a></span>
                            </div>
                        </div>
                        <hr>
                    </div>
                <?php
                }

                ?>





            </div>

            <!-- PAGINACION -->
            <div class="contenedorPaginacion d-flex justify-content-center">

                <nav aria-label="Page navigation example ">
                    <ul class="pagination">

                        <?php
                        $i = 0;
                        $limitePaginacion = 7;
                        $limitacion = false;
                        for ($i; $i < $totalPagina; $i++) {

                            if ($i < $limitePaginacion) {
                                $limitacion = true;

                        ?>
                                <li class="page-item <?php if ($pagina == $i + 1) echo 'active' ?>"><a class="page-link" href="?pagina=<?php echo $i + 1 ?>"> <?php echo $i + 1 ?></a></li>
                            <?php

                            }
                        }

                        if ($limitacion) {
                            ?>
                            <li class="page-item"><a class="page-link disabled" href="">...</a></li>
                        <?php
                        }
                        ?>


                        <li class="page-item <?php if ($pagina >= $i) echo 'disabled' ?>">
                            <a class="page-link" href="?pagina=<?php echo $pagina + 1 ?>" aria-label="Next"> <span aria-hidden="true">&raquo; </span> </a>
                        </li>
                    </ul>
                </nav>

            </div>

        </section>

        <!-- FORMULARIO BUSCAR -->
        <section class="seccionBuscar ">

            <form action="" method="post" class="formularioBuscar">

                <h1>Buscar Aspirante</h1>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="datoBuscar" id="floatingInput" placeholder="hola k hace" required>
                    <label for="floatingInput">Cedula o Apellido*</label>
                </div>

                <div class="form mb-3">

                    <input type="submit" name="buscar" class="form-control boton" id="floatingInput" value="Buscar">

                </div>
            </form>


        </section>

    </main>


    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- VALIDAR FORMULARIO VACIOS -->
    <script src="../../LOGIN/scriptValidarFormulario.js"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <!-- CONFIRMAR ELIMINACION -->
    <script src="./confirmarEliminacion.js"></script>

    <!-- EVITAR EL REENVIO DE LOS FORMULARIO -->
    <script src="../../evitarReenvioFormulario.js"></script>

</body>

</html>