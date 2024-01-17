<?php
session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../index.html');
    die();
}

include("../../conexion.php");



// DATOS PARA LA PAGINACION
$queryTotalEmpresa = mysqli_query($conn, "SELECT * FROM usuario_empresa WHERE estado_cuenta = 1");


$limiteConsulta = 10;

if (empty($_REQUEST['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_REQUEST['pagina'];
}

$desde = ($pagina - 1) * $limiteConsulta;
$total_Empresas = mysqli_num_rows($queryTotalEmpresa);
$totalPaginas = ceil($total_Empresas / $limiteConsulta);




// DATOS DE LAS EMPRESAS PARA PINTAR
$queryEmpresas = mysqli_query($conn, "call adminDatosEmpresa(1,$desde, $limiteConsulta)");
while (mysqli_next_result($conn)) {;
}

// pasar los datos a un array para llamar los datos cuantas veces quiera
$datosEmpresa = array();
while ($recorrer = mysqli_fetch_array($queryEmpresas)) {
    $datosEmpresa[] = $recorrer;
}


// ELIMINAR
if (isset($_REQUEST['id_eliminar'])) {

    $id_usuario_empresa = $_REQUEST['id_eliminar'];
    $queryOcultarCuenta = mysqli_query($conn, "UPDATE usuario_empresa SET estado_cuenta = '0' WHERE (id_usuario_empresa = '$id_usuario_empresa')");

    // si se elimina correctamente se envia el modal de correcto
    if ($queryOcultarCuenta) {
?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalEliminacionCorrecto.js"></script>
        </body>
<?php
    }
}

// BUSCAR EMPRESA
if (isset($_POST['buscarEmpresa'])) {

    $dato = $_POST['datoEmpresa'];
    $buscarEmpresa = mysqli_query($conn, "call adminBuscarDatosEmpresa(1, '$dato')");
    $mostrarDatos = array();
    while ($recorrer = mysqli_fetch_array($buscarEmpresa)) {
        $mostrarDatos[] = $recorrer;
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

    <!-- ANIMACION LIBRERIA -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="estiloEdicionEmpresa.css">
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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../EDICIONASPIRANTE/edicionAspirante.php">Edicion Aspirante</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace activo" aria-current="page">Edicion Empresa</a>
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

        <!-- MOSTRAR EMPRESA -->
        <section class="mb-3 ">

            <!-- DATOS BUSCADOS -->
            <div class="mb-3">

                <?php
                // si existe el dato buscado
                if (isset($mostrarDatos)) {

                    foreach ($mostrarDatos as $mostrarDatos) {
                ?>
                        <div class="cartaAspirante mb-3 bg-dark text-light">
                            <span class="cedula"><?php echo $mostrarDatos['correo'] ?></span>
                            <span class="nombre"><?php echo $mostrarDatos['nombreUsuario'] ?></span>

                            <div class="acciones">
                                <span><a onClick="confirmacion(event)" href="?id_eliminar=<?php echo $mostrarDatos['id_usuario_empresa'] ?>">X</a></span>
                                <span><a href="../../PERFILEMPRESA/perfilEmpresa.php?id_empresa=<?php echo $mostrarDatos['id_usuario_empresa'] ?>&admin=ok">Editar</a></span>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>


            </div>


            <h1>Ultimos registros de Empresas (<?php echo $total_Empresas ?>)</h1>


            <!-- LISTA EMPRESAS -->
            <div class="contenedorTodosAspirantes">

                <div>

                    <?php
                    foreach ($datosEmpresa as $recorrer) {
                    ?>

                        <div class="cartaAspirante">
                            <span class="cedula"><?php echo $recorrer['correo'] ?></span>
                            <span class="nombre"> <?php echo $recorrer['nombreUsuario'] ?></span>





                            <div class="acciones">
                                <span><a onClick="confirmacion(event)" href="?id_eliminar=<?php echo $recorrer['id_usuario_empresa'] ?>">X</a></span>
                                <span><a style="color: blue; text-decoration: underline; cursor: pointer;" onclick="entrarEmpresa(<?php echo $recorrer['id_usuario_empresa'] ?>)">Editar</a></span>
                            </div>
                        </div>
                        <hr>

                    <?php
                    }

                    ?>

                </div>




            </div>

            <!-- PAGINACION -->
            <div class="contenedorPaginacion d-flex justify-content-center">

                <nav aria-label="Page navigation example ">
                    <ul class="pagination">
                        <?php

                        $limitePagina = 7;
                        $i = 0;
                        $mostrarUltimaPagina = false;
                        $mostrarLimitacion = false;

                        for ($i; $i < $totalPaginas; $i++) {

                            if ($i < $limitePagina) {
                                $mostrarUltimaPagina = true;
                                $mostrarLimitacion = true;
                        ?>
                                <li class="page-item <?php if ($i + 1 == $pagina) echo 'active' ?>"><a class="page-link" href="?pagina=<?php echo $i + 1 ?>"><?php echo $i + 1 ?></a></li>
                            <?php
                            }
                        }


                        if ($mostrarUltimaPagina) {

                            if ($mostrarLimitacion) {
                            ?>
                                <li class="page-item disabled"><a class="page-link" href="">...</a></li>

                            <?php
                            }

                            ?>
                            <li class="page-item <?php if ($i == $pagina) echo 'active' ?>"><a class="page-link" href="?pagina=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php
                        }
                        ?>


                        <li class="page-item">
                            <a class="page-link <?php if ($pagina >= $i) echo 'disabled' ?>" href="?pagina=<?php echo $pagina + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>

        </section>

        <!-- FORMULARIO BUSCAR -->
        <section class="seccionBuscar ">

            <form action="" method="post" class="formularioBuscar">

                <h1>Buscar Empresa</h1>

                <div class="form-floating mb-3">
                    <input type="text" name="datoEmpresa" class="form-control" id="floatingInput" placeholder="hola k hace" required>
                    <label for="floatingInput">Nombre de empresa o correo</label>
                </div>

                <div class="form mb-3">

                    <input type="submit" name="buscarEmpresa" class="form-control boton" id="floatingInput" value="Guardar">

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

    <script>
        // entrar a el perfil de empresa por medio de sesion
        const entrarEmpresa = id_empresa => {

            let FD = new FormData()
            FD.append('id_empresa', id_empresa)
            console.log(FD.get('id_empresa'));


            fetch('./queryEntrarEmpresa.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = '../../PERFILEMPRESA/perfilEmpresa.php'
                    }
                })
        }
    </script>
</body>

</html>