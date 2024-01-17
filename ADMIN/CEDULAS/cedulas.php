<?php

session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../index.html');
    die();
}

include("../../conexion.php");

// total cedulas
$queryTotalCedulas = mysqli_query($conn, "SELECT * FROM totalcedulas");
$totalCedula = mysqli_num_rows($queryTotalCedulas);



// OPERACION PAGINACION
$limiteConsulta = 10;

if (empty($_REQUEST['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_REQUEST['pagina'];
}

$desde = ($pagina - 1) * $limiteConsulta;

$totalPaginas = ceil($totalCedula / $limiteConsulta);


// CONSULTA TODAS LAS CEDULAS CON A QUIEN PERTENECEN
$queryCedulas = mysqli_query($conn, "SELECT cedu.id_cedula, cedu.cedula, cedu.nombre as nombreCe, cedu.apellido as apellidoCe, datos.id_datos_estudiantes FROM usuario_estudiantes as usuEs   
                                            RIGHT JOIN cedula as cedu   ON usuEs.id_usuEstudiantes = cedu.fk_id_usuEstudiantes 
                                            LEFT JOIN datos_estudiantes as datos ON usuEs.id_usuEstudiantes = datos.fk_id_usuEstudiantes
                                            LIMIT $desde,$limiteConsulta");
while (mysqli_next_result($conn)) {;
}


// ELIMINAR CEDULA
if (isset($_REQUEST['eliminar'])) {

    $id_cedula = $_REQUEST['eliminar'];
    $queryEliminarCedula = mysqli_query($conn, "DELETE FROM cedula WHERE id_cedula = '$id_cedula' ");
    if ($queryEliminarCedula) {
?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalCorrecto.js"></script>
        </body>
<?php

    }
}




$cedulaBuscar = "";
$queryBuscarCedula = "";

// BUSCAR CEDULA
if (isset($_POST['BotonbuscarCedula'])) {

    if ($_POST['buscarCedula'] == "") {
        echo "<script> alert('Cedula vacia') </script>";
    }

    $cedulaBuscar = $_POST['buscarCedula'];

    $queryBuscarCedula = mysqli_query($conn, "SELECT cedu.id_cedula, cedu.cedula, cedu.nombre as nombreCe, cedu.apellido as apellidoCe, datos.id_datos_estudiantes FROM usuario_estudiantes as usuEs   
                                                    RIGHT JOIN cedula as cedu   ON usuEs.id_usuEstudiantes = cedu.fk_id_usuEstudiantes 
                                                    LEFT JOIN datos_estudiantes as datos ON usuEs.id_usuEstudiantes = datos.fk_id_usuEstudiantes
                                                    WHERE cedu.cedula = '$cedulaBuscar' ");

    if (!$queryBuscarCedula) {
        mysqli_error($conn);
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

    <!-- alerta personalizada -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    <link rel="stylesheet" href="estiloCedulas.css">
    <link rel="stylesheet" href="../estiloHeader.css">
    <title>Cedulas</title>
</head>

<body>

    <header class="header">

        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">
                <!-- 
                <a class="navbar-brand" href="../index.html">
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
                            <a class="nav-link iconoEnlace activo" aria-current="page">Cedulas</a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../CARRERAS/carreras.php">Carreras</a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../EDICIONASPIRANTE/edicionAspirante.php">Edicion Asp</a>
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

    <main class="container main">

        <!-- TABLA CEDULAS -->
        <section class="contenedorMostrarCedulas" data-aos="fade-right">

            <table class="table table-dark table-striped">

                <thead>
                    <tr>
                        <th scope="col">Cedula</th>
                        <th scope="col">Usuario</th>
                    </tr>
                </thead>


                <tbody id="tbody">

                    <?php
                    while ($recorrerCedula = mysqli_fetch_array($queryCedulas)) {
                    ?>
                        <tr>
                            <!-- mostrar cedula y si la cedula no tiene usuario se puede eliminar -->
                            <td>
                                <?php
                                echo $recorrerCedula['cedula'];

                                if ($recorrerCedula['id_datos_estudiantes'] === null || $recorrerCedula['id_datos_estudiantes'] === "") {
                                ?>
                                    <a onclick="confirmacion(event)" href="?eliminar=<?php echo $recorrerCedula['id_cedula'] ?>">X</a>
                                <?php
                                }

                                ?>
                            </td>

                            <td><?php echo $recorrerCedula['nombreCe'], " ", $recorrerCedula['apellidoCe'] ?></td>
                        </tr>
                    <?php
                    }

                    ?>


                </tbody>

            </table>




            <!-- PAGINACION -->
            <div class="paginacion  d-flex justify-content-center">

                <nav aria-label="Page navigation example">

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


        <!-- FORMULARIOS-->
        <section class="seccionFormulario">

            <!-- FORMULARIO AGREGAR -->
            <form id="formulario">

                <div class="titulo">
                    <h3>Agregar Cedula <?php echo "<h5>total($totalCedula)</h5>" ?></h3>
                    <hr>
                </div>

                <div class="input-group mb-3">
                    <input type="number" name="cedula" class="form-control" id="cedula" placeholder="Cedula" required>
                    <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
                    <input type="text" name="apellido" class="form-control" id="apellido" placeholder="Apellido" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="check" value="" id="check">
                    <label class="form-check-label" for="check">
                        <i>En proceso de estudio (beta, no seleccionar)</i>
                    </label>
                </div>


                <div class="form mb-3">

                    <input type="submit" name="guardarCedula" class="form-control boton" id="floatingInput" value="Guardar">

                </div>
            </form>


            <!-- FORMULARIO BUSCAR-->
            <section class="seccionFormulario">


                <form action="" method="post">

                    <div class="titulo">
                        <h3>Buscar Cedula</h3>
                        <hr>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" name="buscarCedula" class="form-control" id="floatingInput" placeholder="Buscar Cedula" required>
                        <label for="floatingInput">Buscar Cedula*</label>
                    </div>

                    <div class="form mb-3">

                        <input type="submit" name="BotonbuscarCedula" class="form-control boton" id="floatingInput" value="Buscar">

                    </div>
                </form>


            </section>

            <!-- TABLA BUSCAR -->
            <?php
            if ($cedulaBuscar != "") {
            ?>

                <table class="table table-dark table-striped">

                    <thead>
                        <tr>
                            <th scope="col">Cedula</th>
                            <th scope="col">Usuario</th>
                        </tr>
                    </thead>


                    <tbody>

                        <?php

                        while ($recorrerBuscarCedula = mysqli_fetch_array($queryBuscarCedula)) {
                        ?>
                            <tr>
                                <!-- mostrar cedula y si la cedula no tiene usuario se puede eliminar -->

                                <td>
                                    <?php
                                    echo $recorrerBuscarCedula['cedula'];

                                    if ($recorrerBuscarCedula['id_datos_estudiantes'] === null || $recorrerBuscarCedula['id_datos_estudiantes'] === "") {
                                    ?>
                                        <a onclick="confirmacion(event)" href="?eliminar=<?php echo $recorrerBuscarCedula['id_cedula'] ?>">X</a>
                                    <?php
                                    }

                                    ?>
                                </td>

                                <td><?php echo $recorrerBuscarCedula['nombreCe'], " ", $recorrerBuscarCedula['apellidoCe'] ?></td>
                            </tr>
                        <?php
                        }

                        ?>


                    </tbody>

                </table>

            <?php
            }
            ?>


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

    <!-- CONFIRMAR ELMINAR CEDULA -->
    <script src="./confirmarEliminarCedula.js"></script>

    <!-- EVITAR EL REENVIO DE LOS FORMULARIO -->
    <script src="../../evitarReenvioFormulario.js"></script>

    <!-- ALERTA PERSONALIZADA -->
    <script src="../../alertaPersonalizada.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <!-- LOGIA VALIDAR CEDULA -->
    <script src="../../validarCedula.js"></script>

    <!-- SCRIPT INSERTAR TARGETA -->
    <script>
        var formulario = document.getElementById('formulario')
        var tabla = document.getElementById('tbody')

        let formdata

        formulario.addEventListener('submit', function(e) {

            e.preventDefault()

            formdata = new FormData(formulario)

            // si la cedula es invalida
            if (!validarCedula(formdata.get('cedula'))) {

                alertaPersonalizada('ERROR', 'Cedula Incorrecta.', 'error', 'Regresar', 'no')
                return
            }


            fetch('./guardarCedula.php', {
                    method: 'POST',
                    body: formdata
                })
                .then(resp => resp.json())
                .then(e => {

                    // si los datos viene vacios
                    if (e.mensaje === 'datos vacios') {
                        alert(e.mensaje)
                        return
                    }


                    // si la cedula ya existe
                    if (e.mensaje === 'La Cedula ya Existe.') {
                        alertaPersonalizada('ERROR', e.mensaje, 'error', 'Regresar', 'no')
                        return
                    }


                    // si todo sale correcto
                    if (e.mensaje === 'ok') {

                        // mostrar los datos en la tabla
                        tabla.innerHTML += `
                        <tr>
                            <td>${e.cedula} <a href='?eliminar=${e.id}'>X</a> </td>
                            <td>${e.nombre} ${e.apellido}</td>
                        </tr>
                    `

                        // limpiar los inputs
                        document.getElementById('cedula').value = ''
                        document.getElementById('nombre').value = ''
                        document.getElementById('apellido').value = ''


                        alertaPersonalizada('CORRECTO', 'Cedula Insertada.', 'success', 'Regresar', 'no')


                    } else if (e.mensaje === 'error consulta') {

                        alertaPersonalizada('ERROR', e.mensaje, 'info', 'Regresar', 'no')

                    }
                })
                .catch(error => console.log(error))
        })
    </script>
</body>

</html>