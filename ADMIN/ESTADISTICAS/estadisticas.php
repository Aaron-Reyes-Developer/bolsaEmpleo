<?php

session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../index.html');
    die();
}

include("../../conexion.php");


// CONSULTA PARA LA TABLA DE ESTADISTICA EMPELO CONFIRMADO
$queryEmpleoConfirmados = mysqli_query($conn, "SELECT ELT(MONTH(fecha),'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre') fecha, count(*) total  FROM econtre_empleo group by MONTH(fecha)");
while (mysqli_next_result($conn)) {;
}



// colocar los datos en un array para poderlo consulta dos veces
$datosEmpleosConfirmados = array();
while ($recorrer = mysqli_fetch_array($queryEmpleoConfirmados)) {
    $datosEmpleosConfirmados[] = $recorrer;
}





// CONSULTA PARA LA TABLA DE ESTADISTICA EMPLEOS APROBADOS
$queryEmpleoAprobados = mysqli_query($conn, "SELECT 
ELT(MONTH(fecha_aprobado),'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre') fecha,
COUNT(*) total
FROM postula
WHERE aprobado = 1
group by MONTH(fecha_aprobado)");

// colocar los datos en un array para poderlo consulta dos veces
$datosEmpleosAprobados = array();
while ($recorrer = mysqli_fetch_array($queryEmpleoAprobados)) {
    $datosEmpleosAprobados[] = $recorrer;
}

$fecha_actual = date('Y-m');

if (isset($_POST['botonBuscar'])) {
    $fecha_actual = $_POST['buscarMes'];
}

// CONSULTA PARA MOSTRAR LOS DATOS DE LA POSTULACION Y PINATERLOS EN EL PANEL DE ESTADISTICAS
$queryDatosPostulacion = mysqli_query($conn, "SELECT 

usuEs.id_usuEstudiantes, 
concat(datosEs.nombre, ' ',datosEs.apellido) as nombreAspirante, 
apro.fecha_postulacion,
apro.fecha_aprobado,
ofer.puesto,	
tip_oft.nombre as tipo_empleo,
usuEm.id_usuario_empresa,
datosEm.nombre as nombreEmpresa
FROM usuario_estudiantes as usuEs

#datos estudiantes
LEFT JOIN datos_estudiantes as datosEs
ON usuEs.id_usuEstudiantes = datosEs.fk_id_usuEstudiantes

#postula
LEFT JOIN postula as apro
ON usuEs.id_usuEstudiantes = apro.fk_id_usuEstudiantes

#oferta de trabajo
LEFT JOIN oferta_trabajo as ofer
ON ofer.id_oferta_trabajo = apro.fk_id_oferta_trabajo

#tipo de empleo
INNER JOIN tipos_oferta tip_oft
ON tip_oft.id_tipo_oferta = ofer.fk_id_tipo_oferta

#usuario empresa
LEFT JOIN usuario_empresa as usuEm
ON usuEm.id_usuario_empresa = ofer.fk_id_usuario_empresa

#datos empresa
LEFT JOIN datos_empresa as datosEm
ON usuEm.id_usuario_empresa = datosEm.fk_id_usuario_empresa
WHERE apro.aprobado = 1 AND apro.fecha_aprobado LIKE '$fecha_actual%'
ORDER BY apro.fecha_aprobado ");
while (mysqli_next_result($conn)) {;
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


    <link rel="stylesheet" href="estiloEstadisticas.css">
    <link rel="stylesheet" href="../estiloHeader.css">
    <title>Estadisticas</title>

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
                            <a class="nav-link iconoEnlace" aria-current="page" href="../CEDULAS/cedulas.php">Cedulas</a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../CARRERAS/carreras.php">Carreras</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../EDICIONASPIRANTE/edicionAspirante.php">Edicion Aspirante</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../EDICIONEMPRESA/edicionEmpresa.php">Edicion Empresa</a>
                        </li>



                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace activo" aria-current="page">Estadisticas</a>
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

        <!-- Cuadro estadistico -->
        <section class="seccionCuadroEstadistico">

            <canvas class="canva" id="myChart"></canvas>
            <canvas class="canva" id="myChart2"></canvas>
        </section>


        <!-- seccion informacion -->
        <section class="seccionInformacion">

            <h1>Estadisticas</h1>


            <!-- FORMULARIO BUSCAR POR MES -->
            <form action="" method="post" class="formulario">
                <span>Total (<?php echo mysqli_num_rows($queryDatosPostulacion); ?>)</span>

                <div class="contenedorImputs">
                    <input class="form-control" type="month" name="buscarMes" value="<?php echo $fecha_actual ?>" required>
                    <input type="submit" name="botonBuscar" value="buscar" class="buscar btn">
                </div>
            </form>


            <hr>

            <div class="contendorCarta">
                <?php

                while ($recorrerDatosPostula = mysqli_fetch_array($queryDatosPostulacion)) {
                ?>
                    <!-- CARTA -->
                    <div class="carta">
                        <h2><?php echo $recorrerDatosPostula['nombreAspirante'] ?></h2>
                        <span><b>Aprobado por: </b> <a href="../../PERFILESPUBLICOS/PERFILEMPRESA/perfilEmpresa.php?id_empresa=<?php echo $recorrerDatosPostula['id_usuario_empresa'] ?>"><?php echo $recorrerDatosPostula['nombreEmpresa'] ?></a> </span><br>
                        <?php $_SESSION['id_empresa'] = 'ok' ?>
                        <span><b>Puesto Oferta: </b><?php echo $recorrerDatosPostula['puesto'] ?> </span><br>
                        <span><b>Tipo de Oferta: </b> <?php echo $recorrerDatosPostula['tipo_empleo'] ?> </span><br>
                        <span><b>Fecha Postulado: </b> <?php echo $recorrerDatosPostula['fecha_postulacion'] ?> </span><br>
                        <span><b>Fecha Aprobado: </b> <?php echo $recorrerDatosPostula['fecha_aprobado'] ?> ✔️</span><br>

                    </div>
                <?php
                }


                ?>
            </div>


        </section>
    </main>




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SCRIPT DEL CUADRO ESTADISTICO EMPLEOS CONFIRMADOS-->
    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php
                    // mostrar los menes que tiene la consulta 
                    foreach ($datosEmpleosConfirmados as $datos) {
                        echo "'" . $datos['fecha'] . "',";
                    }

                    ?>
                ],
                datasets: [{
                    label: 'Empleos por Mes Confirmado',
                    data: [
                        <?php

                        // mostrar los datos de cuantos empleos estan confirmados por mes
                        foreach ($datosEmpleosConfirmados as $datos) {
                            echo  $datos['total'] . ",";
                        }

                        ?>
                    ],
                    borderWidth: 1,
                    backgroundColor: '#04ec64'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


    <!-- SCRIPT DEL CUADRO ESTADISTICO EMPLEOS Aprobados-->
    <script>
        const ctx2 = document.getElementById('myChart2');

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [
                    <?php
                    foreach ($datosEmpleosAprobados as $datosAprobados) {
                        echo "'" . $datosAprobados['fecha'] . "',";
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Empleos Aprobados',
                    data: [
                        <?php
                        foreach ($datosEmpleosAprobados as $datosAprobados) {
                            echo $datosAprobados['total'] . ",";
                        }
                        ?>
                    ],
                    borderWidth: 1,
                    backgroundColor: '#99fcc2'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- VALIDAR FORMULARIO VACIOS -->
    <script src="../../LOGIN/scriptValidarFormulario.js"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>


    <!-- EVITAR EL REENVIO DE LOS FORMULARIO -->
    <script src="../../evitarReenvioFormulario.js"></script>

</body>

</html>