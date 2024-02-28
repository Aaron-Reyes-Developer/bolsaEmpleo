<?php
session_start();


if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: https://trabajounesum.com/index.html');
    die();
}

include("../conexion.php");

// TOTAL DE ASPIRANTES
$queryTotalAspirante = "SELECT count(id_usuEstudiantes) as totalAspirantes FROM usuario_estudiantes WHERE estado_cuenta = 1";
$respuestaTotalAspirante = mysqli_query($conn, $queryTotalAspirante);
$recorrerTotalAspirante = mysqli_fetch_array($respuestaTotalAspirante);
while (mysqli_next_result($conn)) {;
}


// TOTAL DE EMPRESA
$queryTotalEmpresa = "SELECT count(id_usuario_empresa) as totalEmpresas FROM usuario_empresa WHERE estado_cuenta = 1";
$respuestaTotalEmpresa = mysqli_query($conn, $queryTotalEmpresa);
$recorrerTotalEmpresa = mysqli_fetch_array($respuestaTotalEmpresa);
while (mysqli_next_result($conn)) {;
}


// TOTAL OFERTAS
$queryTotalOfertas = "SELECT count(id_oferta_trabajo) as totalOfertas FROM oferta_trabajo";
$respuestaTotalOfertas = mysqli_query($conn, $queryTotalOfertas);
$recorrerTotalOfertas = mysqli_fetch_array($respuestaTotalOfertas);
while (mysqli_next_result($conn)) {;
}

// TOTAL APROBADOS
$queryTotalAprobados = mysqli_query($conn, "SELECT COUNT(*) as totalAprobados FROM postula WHERE aprobado = 1 ");
$recorrerTotalAprobados = mysqli_fetch_array($queryTotalAprobados);


// ASPIRANTES QUE ENCONTRARON TRABAJO
$queryEncontreEmpleo = mysqli_query($conn, 'SELECT 
encon.id_econtre_empleo, 
encon.nombreEmpresa, encon.puesto,  
encon.descipcion, encon.imagen, 
encon.fecha, 
usuEs.id_usuEstudiantes,
datosEs.nombre,
datosEs.apellido,
datosEs.imagen_perfil 
FROM econtre_empleo as encon
LEFT JOIN usuario_estudiantes as usuEs
ON usuEs.id_usuEstudiantes = encon.fk_id_usuEstudiantes
LEFT JOIN datos_estudiantes as datosEs
ON usuEs.id_usuEstudiantes = datosEs.fk_id_usuEstudiantes
ORDER BY encon.fecha DESC
LIMIT 7
');

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
    <link rel="icon" href="https://trabajounesum.com/imagenes/Iconos/iconoAdmin/kitty.gif">

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

    <!-- ALERTA PERSONALIZADA -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    <link rel="stylesheet" href="https://trabajounesum.com/ADMIN/estiloAdmin.css">
    <link rel="stylesheet" href="https://trabajounesum.com/ADMIN/estiloHeader.css">
    <title>Admin</title>
</head>

<body>


    <header class="header">

        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <a class="navbar-brand" href="https://trabajounesum.com/index.html">
                    <img src="https://trabajounesum.com/imagenes/logoUnesum.webp" alt="">
                </a>

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
                                <li><a class="dropdown-item" href="https://trabajounesum.com/ADMIN/EXTRAS/TIPOOFERTAEMPLEO/tipoOferta.php">Tipo Oferta Empleo</a></li>
                                <li><a class="dropdown-item" href="https://trabajounesum.com/ADMIN/EXTRAS/TIPOLUGAREMPLEO/tipoLugarOferta.php">Tipo Lugar Oferta Empleo</a></li>
                                <li><a class="dropdown-item" href="https://trabajounesum.com/ADMIN/EXTRAS/TIPOHORARIO/tipoHorario.php">Tipo Horario Empleo</a></li>
                                <li><a class="dropdown-item" href="https://trabajounesum.com/ADMIN/EXTRAS/PUBLICIDAD/publicidad.php">Publicidad</a></li>
                            </ul>
                        </div>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace activo" aria-current="page">Admin</a>
                        </li>


                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./CEDULAS/cedulas.php">Cedulas</a>
                        </li>

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./CARRERAS/carreras.php">Carreras</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./EDICIONASPIRANTE/edicionAspirante.php">Edicion Asp</a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./EDICIONEMPRESA/edicionEmpresa.php">Edicion Emp</a>
                        </li>


                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="./ESTADISTICAS/estadisticas.php">Estadisticas</a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../cerrarSesion.php"><img src="../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                    </ul>

                </div>


            </div>

        </nav>

    </header>


    <div class="contenedorAgregarCodigoEmpresa" id="contenedorAgregarCodigoEmpresa">
    </div>



    <main class="container main" data-aos="fade-right">

        <!-- TOTALES -->
        <section class="seccionTotalBd">

            <!-- TOTAL ASPIRANTE -->
            <div class="contenedorTotalAspirantes subcontenedorTotal">
                <h2>Total Aspirantes</h2>
                <span><?php echo $recorrerTotalAspirante['totalAspirantes'] ?></span>
            </div>


            <!-- TOTAL EMPRESAS -->
            <div class="contenedorTotalEmpresa subcontenedorTotal">
                <h2>Total Empresas</h2>
                <span><?php echo $recorrerTotalEmpresa['totalEmpresas'] ?></span>
            </div>


            <!-- TOTAL OFERTAS -->
            <div class="contenedorTotalOferta subcontenedorTotal">
                <h2>Total Ofertas</h2>
                <span><?php echo $recorrerTotalOfertas['totalOfertas'] ?></span>
            </div>


            <!-- TOTAL APROBADOS -->
            <div class="contenedorTotalOferta subcontenedorTotal">
                <h2>Total Aprobados</h2>
                <span><?php echo $recorrerTotalAprobados['totalAprobados'] ?></span>
            </div>
        </section>

        <button class="mb-3 btn btn-warning" id="boton">Copia de Seguridad BD</button>
        <button class="mb-3 btn" onclick="mostrarFormularioCodigo()" style="background-color: #04ec64; color: #fff;" id="botonAgregarCodigoEmpresa">Crear Codigo Empresa</button>

        <!-- COMENTARIOS ASPIRANTE-->
        <section class="seccionComentarios mb-3">

            <h2>Comentarios Aspirantes <a href="./COMENTARIOS/comentariosAspirantes.php">Ver todo...</a></h2>
            <hr>


            <div class="contenedorComentarios">

                <?php
                // queryQue muestra todos los comentarios de los aspirantes
                $queryComentariosAspirantes = mysqli_query($conn, "SELECT comen.id_comentario, comen.comentario, comen.fecha , dt.nombre ,dt.imagen_perfil FROM comentarios  comen
                                                                        LEFT JOIN usuario_estudiantes usuEs
                                                                        ON usuEs.id_usuEstudiantes = comen.fk_id_usuEstudiantes
                                                                        LEFT JOIN datos_estudiantes dt
                                                                        ON usuEs.id_usuEstudiantes = dt.fk_id_usuEstudiantes
                                                                        WHERE comen.fk_id_usuEstudiantes IS NOT null ORDER BY comen.id_comentario DESC LIMIT 7 ");

                while ($recorrerComentarioAspirante = mysqli_fetch_array($queryComentariosAspirantes)) {
                ?>
                    <!-- carta aspirante -->
                    <div class="cartaAspirante">

                        <div class="avatar"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerComentarioAspirante['imagen_perfil']) ?>" alt=""></div>

                        <div class="datos">
                            <h3><?php echo $recorrerComentarioAspirante['nombre'] ?></h3>
                            <span><?php echo limitar_cadena($recorrerComentarioAspirante['comentario'], 241, '...') ?></span><br>
                            <span><b>Fecha: </b> <?php echo $recorrerComentarioAspirante['fecha'] ?> </span>
                        </div>


                    </div>

                <?php


                }
                ?>



            </div>


        </section>

        <!-- COMENTARIOS EMPRESA -->
        <section class="seccionComentarios mb-3">

            <h2>Comentarios Empresas <a href="./COMENTARIOS/comentariosEmpresas.php">Ver todo...</a></h2>
            <hr>


            <div class="contenedorComentarios">

                <?php
                // queryQue muestra todos los comentarios de los aspirantes
                $queryComentariosAspirantes = mysqli_query($conn, "SELECT comen.id_comentario, comen.comentario, comen.fecha , dt.nombre,dt.imagen_perfil FROM comentarios  comen
                                                                        LEFT JOIN usuario_empresa usuEm
                                                                        ON usuEm.id_usuario_empresa = comen.fk_id_empresa
                                                                        LEFT JOIN datos_empresa dt
                                                                        ON usuEm.id_usuario_empresa = dt.fk_id_usuario_empresa
                                                                        WHERE comen.fk_id_empresa IS NOT null ORDER BY comen.id_comentario DESC LIMIT 7");

                while ($recorrerComentarioAspirante = mysqli_fetch_array($queryComentariosAspirantes)) {
                ?>
                    <!-- carta aspirante -->
                    <div class="cartaAspirante">

                        <div class="avatar"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerComentarioAspirante['imagen_perfil']) ?>" alt=""></div>

                        <div class="datos">
                            <h3><?php echo $recorrerComentarioAspirante['nombre'] ?></h3>
                            <span><?php echo limitar_cadena($recorrerComentarioAspirante['comentario'], 241, '...') ?></span><br>
                            <span><b>Fecha: </b> <?php echo $recorrerComentarioAspirante['fecha'] ?> </span>
                        </div>


                    </div>

                <?php


                }
                ?>



            </div>


        </section>


        <!-- Aspirantes que encontraron trabajo -->
        <section class="seccionComentarios">

            <h2>Aspirantes que encontraron trabajo <a href="./ENCONTRETRABAJO/encontreTrabajo.php">Ver todo</a></h2>
            <hr>


            <div class="contenedorComentarios">

                <?php


                while ($recorrerEmpleo = mysqli_fetch_array($queryEncontreEmpleo)) {
                ?>
                    <!-- carta aspirante -->
                    <div class="cartaAspirante">

                        <div class="avatar" id="foto"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerEmpleo['imagen_perfil']) ?>" alt=""></div>

                        <div class="datos">
                            <h3><?php echo $recorrerEmpleo['nombre'], " ", $recorrerEmpleo['apellido'] ?></h3>
                            <span><?php echo  limitar_cadena($recorrerEmpleo['descipcion'], 241, '...') ?></span><br>
                            <span><b>Fecha: </b> <?php echo $recorrerEmpleo['fecha'] ?></span>
                        </div>


                        <!-- Button trigger modal -->
                        <a href="./mostrarImagenesEmpleo.php?id_imagen=<?php echo $recorrerEmpleo['id_econtre_empleo'] ?>">Ver foto</a>




                    </div>


                <?php
                }

                ?>




            </div>


        </section>

        <!-- ver codigos de las empresa -->
        <section class="seccionCodigos">

            <h2>Codigos de las empresas</h2>
            <hr>
            <table class="mt-3 table table-secondary table-striped">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre empresa</th>
                        <th scope="col">Ruc</th>
                        <th scope="col">Codigo</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $queryConsultaCodigos = mysqli_query($conn, "SELECT * FROM codigo_empresa");

                    $contador = 0;
                    while ($recorrerCodigoEmpresa = mysqli_fetch_assoc($queryConsultaCodigos)) {
                        $contador += 1;

                    ?>
                        <tr>
                            <th scope="row"><?php echo $contador ?></th>
                            <td><?php echo $recorrerCodigoEmpresa['nombre_empresa'] ?></td>
                            <td><?php echo $recorrerCodigoEmpresa['ruc'] ?></td>
                            <td><?php echo $recorrerCodigoEmpresa['codigo_empresa'] ?></td>
                            <td>
                                <button class="btn btn-outline-danger mt-1" onclick="eliminarCodigoEmpresa(<?php echo $recorrerCodigoEmpresa['id_codigo_empresa'] ?>)">Eliminar</button>
                            </td>
                        </tr>
                    <?php
                    }


                    ?>


                </tbody>
            </table>
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

    <script src="../alertaPersonalizada.js"></script>

    <!-- script alerta personalizada -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        // // LOGICA PARA EL BOTON DE LA COPIA DE SEGURIDAD DE LA BASE DE DATOS
        // var boton = document.getElementById('boton')

        // boton.addEventListener('click', function() {

        //     fetch('https://bolsadeempleounesum.online/ADMIN/copiaDeSeguridad.php')
        //         .then(res => res.json())
        //         .then(e => {

        //             if (e.mensaje == 'Copia de seguridad creada.') {
        //                 alertaPersonalizada('Correcto', e.mensaje, 'success', 'Regresar', 'No')
        //             } else {
        //                 alertaPersonalizada('ERROR', e.mensaje, 'error', 'Regresar', 'No')
        //             }


        //         })
        //         .catch(error => console.log(error))

        // })




        // FUNCION AGREGAR CODIGO DE EMPRESA
        function mostrarFormularioCodigo() {

            document.documentElement.scrollTop = 0;
            document.body.style.overflow = 'hidden';
            const contenedorAgregarCodigoEmpresa = document.getElementById('contenedorAgregarCodigoEmpresa')

            contenedorAgregarCodigoEmpresa.innerHTML = `
                <div class="subContenedorAgregarCodigoEmpresa">

                    <form id="formuladioCodigoEmpresa" class="formularioCodigoEmpresa">

                        <span onclick="cerrarCodigoEmpresa()" class="cerrar">X</span>

                        <h2>Crear Codigo Empresa</h2>

                        <hr>


                        <div class="mb-3">
                            <label for="nombreEmpresa" class="form-label">Nombre de la empresa*</label>
                            <input type="text" class="form-control" id="nombreEmpresa" name="nombreEmpresa" placeholder="Ingresa el nombre de la empresa" required>
                        </div>


                        <div class="mb-3">
                            <label for="rucEmpresa" class="form-label">Ruc de la empresa*</label>
                            <input type="number" class="form-control" id="rucEmpresa" name="rucEmpresa" placeholder="Ingresa el ruc de la empresa" required>
                        </div>


                        <div class="mb-3">
                            <label id="mostrarCodigo" class="form-label">----</label>
                            <button onclick="crearCodigoEmpresa()" type="button" class="btn mx-3" style="background-color: #1b8547; color: #fff;">Crear Codigo</button>
                        </div>


                        <div class="mb-3">
                            <input type="submit" class="form-control" id="nombreEmpresa" value="Guardar" style="background-color: #04ec64; color: #000;">
                        </div>

                    </form>

                </div>
            `

            const formuladioCodigoEmpresa = document.getElementById('formuladioCodigoEmpresa')

            formuladioCodigoEmpresa.addEventListener('submit', function(e) {

                e.preventDefault()

                // obtenemos el codigo que tiene el espan
                const spanMostrar = document.getElementById('mostrarCodigo').textContent

                let formdata = new FormData(formuladioCodigoEmpresa)
                formdata.append('codigo', spanMostrar)



                // MANDAR DATOS A LA BASE DE DATOS  
                fetch('./insertarCodigoEmpresa.php', {
                        method: 'POST',
                        body: formdata
                    })
                    .then(res => res.json())
                    .then(e => {

                        if (e.mensaje === 'ok') {
                            alertaPersonalizada('Correcto', 'Codigo creado Correctamente.', 'success', 'Regresar', 'no')
                        }


                    })


            })
        }


        // CERRAR EL MODAL DE EL CODIGO PARA LA EMPRESA
        function cerrarCodigoEmpresa() {
            const contenedorAgregarCodigoEmpresa = document.getElementById('contenedorAgregarCodigoEmpresa')
            contenedorAgregarCodigoEmpresa.innerHTML = '';
            document.body.style.overflow = 'auto';
        }


        // CREAR EL CODIGO PARA LA EMPRESA 
        function crearCodigoEmpresa() {

            // PARA MOSTRAR EL CODIGO EN EL FORMULARIO
            const spanMostrar = document.getElementById('mostrarCodigo')

            // OBTENER EL NOMBRE DE LA EMPRESA
            const nombreEmpresa = document.getElementById('nombreEmpresa').value

            // SI EL NOMBRE DE LA EMPRESA ESTA VACIO
            if (nombreEmpresa == '') {
                alert('Nombre vacio')
                return
            }

            // MODIFICA EL NOMBRE DE LA EMPRESA REMPLAZANDO LOS ESCIAPOS POR _
            const cadenaModificada = nombreEmpresa.replace(/ /g, "_");

            // CREAMOS UN NUMERO RAMDON
            const numeroRandom = Math.floor(Math.random() * (601 - 100)) + 100;

            // CONCATENAMOS EL NOMBRE Y EL NUMERO RAMDOM Y DOS LETRAS ALAZAR
            let codigoEmpresa = cadenaModificada + '_' + numeroRandom + generarParDeLetrasAleatorias()


            // MOSTRAMOS EL CODIGO EN EL SPAN

            spanMostrar.innerHTML = codigoEmpresa
        }



        // GENERAR DOS LETRAS ALEATORIAS
        function generarParDeLetrasAleatorias() {
            const alfabeto = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; // Puedes usar también "abcdefghijklmnopqrstuvwxyz" para minúsculas
            const longitudAlfabeto = alfabeto.length;

            // Genera dos índices aleatorios dentro del rango del alfabeto
            const indice1 = Math.floor(Math.random() * longitudAlfabeto);
            const indice2 = Math.floor(Math.random() * longitudAlfabeto);

            // Obtiene las dos letras aleatorias
            const letra1 = alfabeto.charAt(indice1);
            const letra2 = alfabeto.charAt(indice2);

            // Concatena las dos letras para formar un par
            const parDeLetras = letra1 + letra2;

            return parDeLetras;
        }



        // EDITAR CODIGO EMPRESA


        // EDITAR CODIGO EMPRESA
        const eliminarCodigoEmpresa = codigo => {

            let FD = new FormData()
            FD.append('codigo', codigo)

            fetch('./consultaEliminarCodigoEmpresa.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {
                        alertaPersonalizada('CORRECTO', 'Eliminado Correctamente', 'success', 'Regresar', 'no')
                        location.reload();
                    }

                })
        }
    </script>


</body>

</html>