<?php

session_start();

if (isset($_SESSION['id_empresa']) == null || $_SESSION['id_empresa'] == "") {
    header("Location: ../../LOGIN/login.php");
}

include("../../conexion.php");
include("../../funciones.php");

// eliminar la session de el id_oferta para que no halla ningun inconveniente
if (isset($_SESSION['id_oferta'])) {
    unset($_SESSION['id_oferta']);
}

$id_empresa = $_SESSION['id_empresa'];


//mostrar todos los datos de la empresa
$queryDatosEmpresa = "SELECT * FROM datos_empresa WHERE fk_id_usuario_empresa = '$id_empresa' ";
$respuestaDatosEmpresa = mysqli_query($conn, $queryDatosEmpresa);
$recorrerDatosEmpresa = mysqli_fetch_array($respuestaDatosEmpresa);
while (mysqli_next_result($conn)) {;
}



//consulta para saber cuantos aspirantes existen
$queryTotalAspirante = "SELECT COUNT(*) as totalAspirante FROM usuario_estudiantes";
$respyestaTotalAspirante = mysqli_query($conn, $queryTotalAspirante);
$recorrerTotalAspirantes = mysqli_fetch_array($respyestaTotalAspirante);
while (mysqli_next_result($conn)) {;
}

// operacion paginacion 
$limiteConsulta = 10;


if (empty($_REQUEST['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_REQUEST['pagina'];
}

$desde = ($pagina - 1) * $limiteConsulta;

$totalAspirante = $recorrerTotalAspirantes['totalAspirante'];
$totalPaginas = ceil($totalAspirante / $limiteConsulta);


//mostrar todos los datos de el aspirante para mostrarlo en la parte de curriculum
$queryDatosEstudiantes = "SELECT 
usu.id_usuEstudiantes, 
concat(dat.nombre,' ',dat.apellido) nombres, 
dat.imagen_perfil,
cv.detalle_curriculum,
car.nombre_carrera,
cv.especializacion_curriculum,
cv.estado_trabajo
FROM usuario_estudiantes as usu 
LEFT JOIN datos_estudiantes as dat 
ON usu.id_usuEstudiantes = dat.fk_id_usuEstudiantes 
INNER JOIN carreras car 
ON car.id_carrera = dat.fk_id_carrera
LEFT JOIN curriculum as cv 
ON usu.id_usuEstudiantes = cv.fk_id_usuEstudiantes 
LEFT JOIN conocimientos as cono 
ON cv.id_curriculum = cono.fk_id_curriculum 
LEFT JOIN experiencia as xp 
ON cv.id_curriculum = xp.fk_id_curriculum 
LEFT JOIN educacion as edu 
ON cv.id_curriculum = edu.fk_id_curriculum 
WHERE usu.estado_cuenta = 1  
group by usu.id_usuEstudiantes 
ORDER BY usu.id_usuEstudiantes 
DESC LIMIT $desde, $limiteConsulta";


$respyestaDatosEstudiantes = mysqli_query($conn, $queryDatosEstudiantes);
while (mysqli_next_result($conn)) {;
}


//consulta para saber si existen postulantes en las ofertas de trabajo
$queryPostulantesEnOferta = "call consultaCuantosAspirantesEstanEnUnaOferta('$id_empresa',0,10)";
$respuestaPostulantesEnOferta = mysqli_query($conn, $queryPostulantesEnOferta);
while (mysqli_next_result($conn)) {;
}


//  CONSULTA CARRERAS
$queryCarreras = mysqli_query($conn, "SELECT * FROM carreras WHERE estado = 1");



// notificacion
$queryNotificacion = mysqli_query($conn, "call notificacionEmpresa('$id_empresa')");
$n_r_notificacion = mysqli_num_rows($queryNotificacion);
while (mysqli_next_result($conn)) {;
}


// si se apreta 'marcar como leido en la notificaion
if (isset($_REQUEST['notifiUpdate'])) {

    $actualizarNotificacion = mysqli_query($conn, "UPDATE postula SET estado_noti_empresa = '0' WHERE fk_id_usuario_empresa = '$id_empresa' AND estado_noti_empresa = 1 ");
    if ($actualizarNotificacion > 0) {
        header('Location: ./inicioEmpresa.php');
    }
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

<body id="body">

    <header class="">
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <a class="navbar-brand" href="#">
                    <img src="../../imagenes/Iconos/iconoAdmin/iconoPaginas.gif" style="width: 50px;" alt="">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav">

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./inicioEmpresa.php" title="Iinicio">
                                <img src="../../imagenes/Iconos/casa.svg" alt="Inicio"></a>
                        </li>

                        <li class="nav-item iconoLisa inconoPostulante">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./postulantes.php">Postulantes</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./postulantes.php"><img src="../../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>

                        <!-- icono campana -->
                        <li class="nav-item iconoLisa iconoCampana">

                            <?php

                            // si existe notificacion
                            if ($n_r_notificacion >= 1) {


                            ?>
                                <div class="dropdown">

                                    <!-- icono campana con la notificacion -->
                                    <a style="text-decoration: none; position: relative;  cursor: pointer;" class="iconoEnlace" data-bs-toggle="dropdown" aria-expanded="false">

                                        <img src="../../imagenes/Iconos/campana.svg" alt="">

                                        <div class="bg-danger rounded text-light numero_icono" style="position: absolute;top: -15px;right: -10px; min-width: 20px; display: flex; justify-content: center; align-items: center;">
                                            <?php echo $n_r_notificacion ?>
                                        </div>
                                    </a>

                                    <ul class="dropdown-menu">

                                        <?php

                                        while ($recorrerNoti = mysqli_fetch_array($queryNotificacion)) {

                                        ?>
                                            <li>
                                                <a onclick="irOferta(<?php echo $recorrerNoti['id_oferta_trabajo'] ?>)" class="dropdown-item notificacionTexto">
                                                    <?php echo "Aspirantes en: " . "<b>" . $recorrerNoti['puesto'] . "</b>" ?>
                                                </a>
                                            </li>

                                        <?php
                                        }
                                        ?>

                                        <hr>
                                        <li><a class="dropdown-item marcarComoLeido" href="?notifiUpdate=ok">Marcar como leido</a></li>
                                        <li><a class="dropdown-item" href="../NOTIFICACIONES/notificaciones.php">Ver todas las notifi...</a></li>
                                    </ul>
                                </div>
                            <?php


                                // si no existe notificacion
                            } else {
                            ?>
                                <div class="dropdown">

                                    <!-- icono campana con la notificacion -->
                                    <a class="iconoEnlace" data-bs-toggle="dropdown" aria-expanded="false">

                                        <img src="../../imagenes/Iconos/campana.svg" alt="">
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item">Sin Notificaciones 😥</a></li>
                                        <hr>
                                        <li><a class="dropdown-item" href="../NOTIFICACIONES/notificaciones.php">Ver todas la notificaciones...</a></li>
                                    </ul>
                                </div>



                            <?php
                            }


                            ?>

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

        <!-- MAIN (formulario y aspireantes)-->
        <section class="curriculumDeLosAspirantes" id="contenedorTotalCarta">

            <h2>Currilum de los Aspirantes</h2>

            <h5 id="totalAspirante">Total Aspirantes (<?php echo $totalAspirante ?>)</h5>
            <hr>


            <!-- formularios -->
            <div class="contenedor_formularios">

                <form class="formulario formularioBuscar" id="formulario">

                    <!-- buscar -->
                    <div class="mb-3 ">
                        <input type="text" name="apellido" class="form-control" id="apellido" placeholder="Buscar por Apellido">
                    </div>

                    <!-- Filtrar por carrera -->
                    <div class="mb-3 ">


                        <select class="form-select " name="filtrar_carrera" id="filtrar_carrera" aria-label="Default select example">
                            <option selected value="" disabled>Selecciona una carrera</option>
                            <option value="">Ninguno</option>
                            <?php
                            while ($recorrarCarrea = mysqli_fetch_array($queryCarreras)) {
                                $nombreCarrea = $recorrarCarrea['nombre_carrera'];
                            ?>
                                <option value="<?php echo $nombreCarrea ?>"> <?php echo $nombreCarrea ?> </option>
                            <?php
                            }
                            ?>

                        </select>

                    </div>

                    <!-- Filtrar por estado -->
                    <div class="mb-3 ">


                        <select class="form-select " name="filtrar_estado" id="filtrar_estado" aria-label="Default select example">
                            <option selected value="" disabled>Selecciona un estado</option>
                            <option value="">Ninguno</option>

                            <?php
                            $queryTipodeOferta = mysqli_query($conn, "SELECT * FROM tipo_estado_trabajo");

                            while ($recorrarTipoOferta = mysqli_fetch_array($queryTipodeOferta)) {

                            ?>
                                <option value="<?php echo $recorrarTipoOferta['nombre'] ?>"><?php echo $recorrarTipoOferta['nombre'] ?></option>


                            <?php
                            }
                            ?>

                        </select>

                    </div>

                    <!-- Filtrar por especializacion -->
                    <div class="mb-3 ">


                        <select class="form-select " name="especializacion" id="especializacion" aria-label="Default select example">

                            <option selected value="" disabled>Selecciona una especializacion</option>

                            <?php
                            // mostrar las especialidades que existen
                            $queryEspecialidades = mysqli_query($conn, "SELECT especializacion_curriculum FROM curriculum group by especializacion_curriculum");

                            while ($recorrerEspecialidades = mysqli_fetch_array($queryEspecialidades)) {
                            ?>
                                <option value="<?php echo $recorrerEspecialidades['especializacion_curriculum'] ?>"> <?php echo $recorrerEspecialidades['especializacion_curriculum'] ?> </option>
                            <?php
                            }

                            ?>


                        </select>

                    </div>


                    <input type="submit" value="Buscar" class="btn btn-primary botonGuardar mb-3">

                </form>

            </div>


            <!-- CARTA ASPIRANTE -->
            <div class="contenedor_aspirante" id="contenedor_aspirante">

                <?php
                while ($recorrerDatosEstudiantes = mysqli_fetch_array($respyestaDatosEstudiantes)) {


                    // SI EL DETALLE CURRICULUM ESTA VACIO
                    if ($recorrerDatosEstudiantes['detalle_curriculum'] == null) {
                        $detalle_curriculum = "<i>Sin detalle</i>";
                    } else {
                        $detalle_curriculum = $recorrerDatosEstudiantes['detalle_curriculum'];
                    }


                    // SI LA ESPECIALIZACION VIENE VACIO
                    if ($recorrerDatosEstudiantes['especializacion_curriculum'] == null) {
                        $especializacion_curriculum = "<i>Sin Especialización</i>";
                    } else {
                        $especializacion_curriculum = $recorrerDatosEstudiantes['especializacion_curriculum'];
                    }

                ?>

                    <!-- CARTA -->
                    <div class="cartaAspirante">

                        <!-- avatar -->
                        <div class="contenedor_imagen_avatar">
                            <div class="contenedor_avatar">
                                <img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerDatosEstudiantes['imagen_perfil']) ?>" alt="">
                            </div>
                        </div>

                        <!-- datos -->
                        <div class="conetenedorDatos">
                            <h3><?php echo $recorrerDatosEstudiantes['nombres'] ?></h3>


                            <p> <?php echo limitar_cadena($detalle_curriculum, 150, '...')  ?> </p>


                            <ul>
                                <li> <b>Titulo: </b> <?php echo $recorrerDatosEstudiantes['nombre_carrera'] ?> </li>
                                <li> <b>Especialidad: </b> <?php echo $especializacion_curriculum ?> </li>
                            </ul>

                        </div>

                        <a onclick="irAspirante(<?php echo $recorrerDatosEstudiantes['id_usuEstudiantes'] ?>)" href="">Ver detalles...</a>

                    </div>

                <?php

                }


                ?>


                <!-- PAGINACION PHP-->
                <div class="paginacion" id="paginacion">

                    <nav aria-label="Page navigation example">

                        <ul class="pagination">

                            <?php
                            $i = 0;
                            $limitePaginacion = 7;
                            $limitacion = false;

                            for ($i; $i < $totalPaginas; $i++) {

                                if ($i < $limitePaginacion) {
                                    $limitacion = true;
                            ?>
                                    <li class="page-item <?php if ($pagina == $i + 1) echo 'active' ?>"><a class="page-link" href="?pagina=<?php echo $i + 1 ?>"><?php echo $i + 1 ?></a></li>
                                <?php
                                }
                            }

                            if ($limitacion) {
                                ?>
                                <li class="page-item disabled"><a class="page-link" href="">...</a></li>
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


            </div>


            <!-- PAGINACION JS -->
            <div class="paginacion mt-3" id="paginacionJs">

                <button class="btn botonGuardar" onclick="mostrarMasDatos('si')">Mostrar más...</button>

            </div>


        </section>

        <!-- SECCION DERECHA -->
        <section class="Postulantes">

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
                        <a onclick="irOferta(<?php echo $id_oferta ?>)" style="color: blue; text-decoration: underline; cursor: pointer;">Ver aspirantes...</a>
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


        // LOGICA BUSCAR
        const formulario = document.getElementById('formulario')
        const contenedor_aspirante = document.getElementById('contenedor_aspirante')
        const totalAspiranteMostrar = document.getElementById('totalAspirante')
        const paginacion = document.getElementById('paginacion')
        const body = document.getElementById('body')
        const contenedorTotalCarta = document.getElementById('contenedorTotalCarta')
        const paginacionJs = document.getElementById('paginacionJs')

        paginacionJs.style.display = 'none'


        let pedirDatos = false
        let cartaFinal = null

        // CUANDO SE BUSQUE UN ELEMENTO
        formulario.addEventListener('submit', function(e) {



            e.preventDefault()

            contenedor_aspirante.innerHTML = ''

            // VERIFICAR LOS DATOS PARA BUSCAR
            let apellido = document.getElementById('apellido').value
            let filtrar_carrera = document.getElementById('filtrar_carrera').value
            let filtrar_estado = document.getElementById('filtrar_estado').value
            let especializacion = document.getElementById('especializacion').value

            FD = new FormData()

            if (apellido != '') {
                FD.append('apellido', apellido)
            }
            if (filtrar_carrera != '') {
                FD.append('filtrar_carrera', filtrar_carrera)
            }
            if (filtrar_estado != '') {
                FD.append('filtrar_estado', filtrar_estado)
            }
            if (especializacion != '') {
                FD.append('especializacion', especializacion)
            }

            mostrarMasDatos('nop')



        })


        // HACER PETICION
        async function mostrarMasDatos(avanzar) {

            paginacionJs.style.display = 'flex'

            // pagina
            if (avanzar !== 'si') {
                pagina = 1
            } else {
                let pagina = parseInt(contenedor_aspirante.dataset.p)
            }

            // no se porque funciona pero funciona
            if (avanzar === 'si') {
                contenedor_aspirante.dataset.p = ++pagina
            }

            FD.append('pagina', pagina)


            // peticion
            const res = await fetch('./consultaBuscar.php', {
                method: 'Post',
                body: FD
            })
            const respuesta = await res.json()



            // desaparecer los elementos que estaban antes
            let totalAspirante = respuesta.length
            totalAspiranteMostrar.innerHTML = `Total Aspirante (${totalAspirante})`



            // MOSTRAR LOS DATOS
            respuesta.forEach(recorrer => {

                // SI EL DETALLE ESTA VACIO
                if (recorrer.detalle_curriculum == null) {
                    detalle_curriculum = '<i>Sin detalle </i>'
                } else {
                    detalle_curriculum = recorrer.detalle_curriculum
                }



                // SI LA ESPECIALIZACION ESTA VACIO
                if (recorrer.especializacion_curriculum == null) {
                    especializacion_curriculum = '<i>Sin Especialización  </i>'
                } else {
                    especializacion_curriculum = recorrer.especializacion_curriculum
                }

                contenedor_aspirante.innerHTML += `
    
                    <div class="cartaAspirante">


                        <div class="contenedor_imagen_avatar">
                            <div class="contenedor_avatar">
                                <img src="data:image/jpeg;base64,${recorrer.imagen_perfil}" alt="">
                            </div>
                        </div>


                        <div class="conetenedorDatos">
                            <h3>${recorrer.nombres}</h3>
                            <p> ${detalle_curriculum} </p>

                            <ul>
                                <li> <b>Titulo: </b> ${recorrer.nombre_carrera} </li>
                                <li> <b>Especialidad: </b> ${especializacion_curriculum}  </li>
                            </ul>

                        </div>

                        <a href="../../PERFILESPUBLICOS/PERFILASPIRANTE/perfilAspirante.php?id_aspirante=${recorrer.id_usuEstudiantes}">Ver detalles...</a>

                    </div>

                `


            })



        }

        // IR A PERFIL ASPIRANTE
        const irAspirante = id => {

            FD = new FormData();
            FD.append('id', id)

            fetch('queryIrAspirante.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = '../../PERFILESPUBLICOS/PERFILASPIRANTE/perfilAspirante.php'
                    }


                })

        }


        // ir a el detalle de la oferta
        const irOferta = id => {

            FD_ir = new FormData()
            FD_ir.append('id_oferta', id)



            fetch('../../queryIrOferta.php', {
                    method: 'POST',
                    body: FD_ir
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        window.location.href = '../../VEROFERTACONASPIRANTES/verOfertasConAspirantes.php'
                    }

                })
        }
    </script>
</body>

</html>