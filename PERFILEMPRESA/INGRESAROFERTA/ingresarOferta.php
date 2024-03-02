<?php
session_start();

if (!isset($_SESSION['id_empresa'])) {
    header("Location: ../../LOGIN/login.php");
    die();
}

include("../../conexion.php");



$id_empresa = $_SESSION['id_empresa'];

// CONSULTA PARA MOSTRAR LOS DATOS EN LO INPUTS CUANDO SE QUIERA EDITAR LA OFERTA
if ((isset($_REQUEST['editar']) && isset($_REQUEST['id_oferta']))  && ($_REQUEST['editar'] == "ok" && $_REQUEST['id_oferta'] != "")) {

    $id_oferta = $_REQUEST['id_oferta'];

    // consulta para editar
    $queryOferta = mysqli_query($conn, "call detalleOferta($id_empresa, $id_oferta)");
    $recorrerOferta = mysqli_fetch_array($queryOferta);

    if ($recorrerOferta['estado_oferta'] == 0) {
        header('Location: ../perfilEmpresa.php');
        die();
    }

    while (mysqli_next_result($conn)) {;
    }


    // Consulta Requisitos
    $queryRequisitos = mysqli_query($conn, "SELECT * FROM requisitos WHERE fk_id_oferta_trabajo = $id_oferta");
    while (mysqli_next_result($conn)) {;
    }
}


//  CONSULTA CARRERAS
$queryCarreras = mysqli_query($conn, "SELECT * FROM carreras WHERE estado = 1");



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../imagenes/Iconos/iconoAdmin/iconoPaginas.gif">



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


    <link rel="stylesheet" href="./estiloOferta.css">
    <title>Ingresar Oferta</title>
</head>

<body>

    <button onclick="irAtras()" class="botonAtras btn "> &#10096; </button>


    <main class="main">

        <!-- SECCION FORMULARIO -->
        <section class="seccionFormulario" data-aos="fade-left">

            <form id="formulario" class="fromulario needs-validation" novalidate>

                <div class="tituloFormulario">
                    <h1>Nueva Oferta</h1>
                    <hr>
                </div>

                <!-- NOMBRE PUESTO -->
                <div class="mb-5">
                    <label for="nombrePuesto" class="form-label">Nombre Puesto*</label>
                    <input type="text" class="form-control input" name="nombrePuesto" list="datalistOptions" id="nombrePuesto" placeholder="" value="<?php if (isset($recorrerOferta)) echo $recorrerOferta['puesto'] ?>" required>
                </div>


                <!-- NOMBRE PUESTO -->
                <div class="mb-5">
                    <label for="plazasTrabajo" class="form-label">Plazas de trabajo*</label>
                    <input type="number" class="form-control input" name="plazasTrabajo" list="datalistOptions" id="plazasTrabajo" placeholder="Plazas de trabajo..." value="<?php if (isset($recorrerOferta)) echo $recorrerOferta['plaza'] ?>" required>
                </div>


                <!-- PRECIO -->
                <div class="mb-5">
                    <label for="precio" class="form-label">Sueldo Mensual aproximado</label>
                    <input type="number" step="0.01" class="form-control input" name="precio" list="datalistOptions" id="precio" placeholder="No ingresar si el sueldo es privado" value="<?php if (isset($recorrerOferta)) echo $recorrerOferta['precio'] ?>">
                </div>


                <!-- TIPO DE EMPLEO -->
                <div class="mb-5">

                    <label for="tipo_empleo" class="form-label">Tipo de empleo*</label>

                    <select class="form-select" name="tipo_empleo" aria-label="Default select example" required>

                        <option selected disabled value="">Selecione el tipo de empleo</option>

                        <?php

                        $queryTipoEmpleo = mysqli_query($conn, "SELECT * FROM tipos_oferta ORDER BY id_tipo_oferta DESC");

                        while ($recorrerTipoEmpleo = mysqli_fetch_array($queryTipoEmpleo)) {
                        ?>
                            <option value="<?php echo $recorrerTipoEmpleo['id_tipo_oferta'] ?>"><?php echo $recorrerTipoEmpleo['nombre'] ?></option>
                        <?php
                        }
                        ?>


                        <!-- este es el input que solo aparece cuando se va a editar la oferta -->
                        <?php
                        if (isset($recorrerOferta)) {
                        ?>
                            <!-- esta opcion solo aparece cuando se va a editar la oferta -->
                            <option selected value="<?php echo $recorrerOferta['id_tipo_oferta'] ?>"><?php echo $recorrerOferta['tipo_oferta'] ?></option>

                        <?php
                        }
                        ?>

                    </select>
                </div>


                <!-- TIPO DE LUGAR -->
                <div class="mb-5">

                    <label for="tipo_lugar" class="form-label">Tipo de lugar*</label>

                    <select class="form-select" name="tipo_lugar" aria-label="Default select example" required>
                        <option selected disabled value="">Selecione el tipo de lugar</option>

                        <?php

                        if (isset($recorrerOferta)) {
                        ?>
                            <!-- esta opcion solo aparece cuando se va a editar la oferta -->
                            <option selected value="<?php echo $recorrerOferta['id_tipo_lugar_oferta'] ?>"><?php echo $recorrerOferta['tipo_lugar'] ?></option>

                        <?php
                        }


                        // mustra las opciones desde la bd
                        $queryLugarOferta = mysqli_query($conn, "SELECT * FROM tipo_lugar_oferta ORDER BY id_tipo_lugar_oferta DESC");

                        while ($recorrerLugarOferta = mysqli_fetch_array($queryLugarOferta)) {
                        ?>

                            <option value="<?php echo $recorrerLugarOferta['id_tipo_lugar_oferta'] ?>"><?php echo $recorrerLugarOferta['nombre'] ?></option>

                        <?php
                        }
                        ?>

                    </select>
                </div>


                <!-- CARRERA DIRIGIDA -->
                <div class="mb-5">

                    <label for="tipo_carrera" class="form-label">Carrera Dirigida*</label>

                    <select class="form-select" name="tipo_carrera" aria-label="Default select example" required>
                        <option selected disabled value="">Selecione el tipo de carrera</option>

                        <?php
                        while ($recorrarCarrea = mysqli_fetch_array($queryCarreras)) {

                        ?>
                            <option value="<?php echo $recorrarCarrea['id_carrera'] ?>"> <?php echo $recorrarCarrea['nombre_carrera'] ?> </option>
                        <?php
                        }

                        ?>

                        <?php
                        if (isset($recorrerOferta)) {
                        ?>
                            <!-- esta opcion solo aparece cuando se va a editar la oferta -->
                            <option selected value="<?php echo $recorrerOferta['id_carrera'] ?>"><?php if (isset($id_oferta)) echo $recorrerOferta['nombre_carrera'] ?></option>

                        <?php
                        }

                        ?>

                    </select>

                </div>


                <!-- UBICACION DE EMPLEO -->
                <div class="mb-5">
                    <label for="ubicacion_empleo" class="form-label">Ubicacion del Empleo*</label>
                    <input type="text" class="form-control input" name="ubicacion_empleo" list="datalistOptions" id="ubicacion_empleo" placeholder="ej: Jipijapa" value="<?php if (isset($recorrerOferta)) echo $recorrerOferta['ubicacion_empleo'] ?>" required>

                </div>


                <!-- TAREAS A REALIZAR -->
                <div class="mb-5">
                    <label for="tareas_realizar" class="form-label">Tareas a Realizar*</label>
                    <textarea class="form-control" name="tareas_realizar" id="tareas_realizar" rows="7" required><?php if (isset($recorrerOferta)) echo $recorrerOferta['tareas_realizar'] ?></textarea>
                </div>


                <!-- DETALLE EMPLEO -->
                <div class="mb-5">
                    <label for="detalle_empleo" class="form-label">Detalle empleo*</label>
                    <textarea class="form-control" name="detalle_empleo" id="detalle_empleo" rows="7" required><?php if (isset($recorrerOferta)) echo $recorrerOferta['detalle'] ?></textarea>
                </div>


                <!-- REQUISITOS -->
                <div class="mb-5" id="contenedorRequisitos" data-n="1">

                    <hr>

                    <?php

                    // entra si se va a editar
                    if (isset($recorrerOferta)) {

                        $contadorRequisito = 0;
                        while ($rowRequisitos = mysqli_fetch_assoc($queryRequisitos)) {
                            $contadorRequisito += 1;
                    ?>
                            <input type="text" name="requisito<?php echo $contadorRequisito ?>" class="form-control mb-3" placeholder="Requisito 1" value="<?php echo $rowRequisitos['detalle'] ?>" required>

                        <?php

                        }
                    } else {
                        // entra si no se esta editando
                        ?>
                        <input type="text" name="requisito1" class="form-control mb-3" placeholder="Requisito 1" required>
                    <?php
                    }

                    ?>

                </div>


                <!-- BOTON AGREGAR MAS -->
                <?php


                // entra si no existe la edicion
                if (!isset($recorrerOferta)) {
                ?>
                    <span onclick="agregarRequisito()" class="btn btn-primary mb-3">Agregar Requisito + </span>
                <?php
                }

                ?>


                <!-- HORARIO -->
                <div class="mb-3">


                    <div class="mb-3">

                        <label for="tipo_lugar" class="form-label">Horario*</label>

                        <select class="form-select" name="horario" aria-label="Default select example" required>
                            <option disabled value="">Selecione el horario</option>


                            <?php

                            // si existe el editar empleo
                            if (isset($recorrerOferta)) {
                            ?>
                                <option value="<?php echo $recorrerOferta['id_tipo_horario_oferta'] ?>" selected><?php echo $recorrerOferta['hora'] ?></option>
                            <?php
                            }

                            $queryHorarioOferta = mysqli_query($conn, "SELECT * FROM tipo_horario_oferta ORDER BY id_tipo_horario_oferta DESC");

                            while ($recorrerHorarioOferta = mysqli_fetch_array($queryHorarioOferta)) {
                            ?>

                                <option value="<?php echo $recorrerHorarioOferta['id_tipo_horario_oferta'] ?>"><?php echo $recorrerHorarioOferta['nombre'] ?></option>

                            <?php
                            }
                            ?>

                        </select>
                    </div>


                </div>


                <!-- GUARDADR -->
                <div class="mb-3">
                    <input type="submit" value="Guardar" class="form-control input botonGuardar" name="guardar">
                </div>


                <!-- ELIMINAR -->
                <?php
                if (isset($_REQUEST['editar']) && isset($_REQUEST['id_oferta'])) {
                ?>
                    <!-- ELIMINAR -->
                    <div class="mb-3">
                        <button class="btn btn-danger" onclick="eliminarOferta(<?php echo $_REQUEST['id_oferta'] ?>)">Eliminar</button>
                    </div>
                <?php
                }
                ?>

            </form>

        </section>


    </main>



    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script src="../../evitarReenvioFormulario.js"></script>

    <script src="../validarFormulario.js"></script>

    <script src="./alertaPersonlizadaNew.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>


    <script>
        AOS.init();

        let formulario = document.getElementById('formulario')
        let contenedorRequisitos = document.getElementById('contenedorRequisitos')
        let numero = Number(contenedorRequisitos.dataset.n)


        // caputra el id de la oferta, si no existe, por defecto se pone '0'
        let id_oferta = <?php echo $id_oferta ?? 0 ?>


        // agregar nuevo input para el requisito
        const agregarRequisito = _ => {

            numero = ++numero

            contenedorRequisitos.insertAdjacentHTML('beforeend', `
                <input type="text" name="requisito${numero}" class="form-control mb-3" placeholder="Requisito ${numero} " required>
            `)

        }


        // guardar datos
        formulario.addEventListener('submit', function(e) {

            e.preventDefault();

            // Selecciona todos los inputs dentro del contenedor
            var inputs = contenedorRequisitos.querySelectorAll('input');

            // Obtiene la cantidad de inputs
            var cantidadInputs = inputs.length;


            FD = new FormData(formulario)
            FD.append('totalRequisito', cantidadInputs)
            FD.append('id_oferta', id_oferta)


            // parar todo si los datos vienen vacios
            if ((FD.get('nombrePuesto') == '') ||
                (FD.get('tipo_empleo') == '') ||
                (FD.get('tipo_lugar') == '') ||
                (FD.get('tipo_carrera') == '') ||
                (FD.get('ubicacion_empleo') == '') ||
                (FD.get('tareas_realizar') == '') ||
                (FD.get('detalle_empleo') == '') ||
                (FD.get('requisito1') == '')) {
                return
            }


            fetch('queryIngresarOferta.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {



                    if (e.mensaje !== 'ok') {
                        alertaPersonalizada('ERROR', 'error')
                        return
                    }

                    if (e.mensaje === 'ok') {
                        alertaPersonalizada('CORRECTO', 'success')
                    }


                })


        })


        const eliminarOferta = id => {

            FD_eliminar = new FormData()

            FD_eliminar.append('id', id)

            fetch('queryEliminarOferta.php', {
                    method: 'POST',
                    body: FD_eliminar
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje === 'ok') {

                        if (e.mensaje !== 'ok') {
                            alertaPersonalizada('ERROR', 'error')
                            return
                        }

                        if (e.mensaje === 'ok') {
                            alertaPersonalizada('CORRECTO', 'success')
                        }

                    }

                })
        }


        // ir atras (boton)
        const irAtras = () => {
            window.history.back();
        }
    </script>
</body>

</html>