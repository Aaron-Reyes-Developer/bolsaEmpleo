<?php
session_start();

//si se ingresa por url sin antes pasar pro el por el primer registro
if ($_SESSION['id_usuario'] == null || $_SESSION['id_usuario'] == "") {
    header("Location: ../../../LOGIN/login.php");
    die();
}

include("../../../conexion.php");

//  CONSULTA CARRERAS PARA PONERLA EN EL SELECT DEL FORMULARIO
$queryCarreras = mysqli_query($conn, "SELECT * FROM carreras WHERE estado = 1");
$datosCarrera = array();
while ($recorrerCarreras = mysqli_fetch_array($queryCarreras)) {
    $datosCarrera[] = $recorrerCarreras;
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../imagenes/iconos/iconoAdmin/iconoPaginas.gif">

    <!-- FUENTES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="header.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital@1&display=swap" rel="stylesheet">


    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- ALERTA PERSONALIZAD -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    <!-- ANIMACION LIBRERIA -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="estiloAspirante.css">
    <title>Registro Aspirante</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../index.html">
                    <img src="../../../imagenes/logoUnesum.png" alt="Unesum">
                </a> -->

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav ">

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../../../index.html">Inicio</a>
                        </li>




                    </ul>

                </div>


            </div>

        </nav>
    </header>

    <main class="main">

        <!-- PORTADA  -->
        <section class="seccion-portada" data-aos="fade-right">
            <div class="contenedor-imagen-portada">
                <img src="../../../imagenes/portada-registro-aspirante.svg" alt="">
            </div>
        </section>

        <!-- FORMULARIO -->
        <section class="seccion-formulario" data-aos="fade-left">

            <form id="formulario" class="formulario needs-validation" enctype="multipart/form-data">

                <!--Avatar-->
                <div class="contenedor-avatar mb-3 container">

                    <!-- IMAGEN -->
                    <div class="mb-2 contenedorImagen">
                        <img id="mostrarImagen" src="../../../imagenes/FotoperfilDefectoAspiranteHombre.jpg" class="rounded-circle" alt="example placeholder" style="width: 200px;" />
                    </div>

                    <!-- BOTON -->
                    <div class="contenedor-boton-imagen">
                        <input class="form-control boton-imagen" name="imagen" accept="image/*" type="file" id="inputImagen">
                    </div>

                </div>

                <div class="container">

                    <div class="row contenedor-inputs">

                        <!-- INPUT NOMBRE -->
                        <div class="contedorCorreo has-validation col-md-6 mb-3 ">

                            <label for="nombre" class="form-label">Nombres*</label>
                            <input readonly type="text" name="nombre" id="nombre" class="form-control" aria-describedby="emailHelp" value="<?php echo $_REQUEST['nombre'] ?>">

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa tu nombre.
                            </div>

                        </div>

                        <!-- INPUT APELLIDO -->
                        <div class="contedorApellido has-validation col-md-6 mb-3">

                            <label for="apellido" class="form-label">Apellidos*</label>
                            <input readonly type="text" name="apellido" id="apellido" class="form-control" aria-describedby="emailHelp" value="<?php echo $_REQUEST['apellido'] ?>" required>

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa tu apellido.
                            </div>

                        </div>

                        <!-- INPUT NOMBRE USUARIO -->
                        <div class="contedorNombreUsuario has-validation col-md-6 mb-3">

                            <label for="nombreUsuario" class="form-label">Nombre de Usuario*</label>
                            <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" aria-describedby="emailHelp" required>

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa un nombre de usuario.
                            </div>

                        </div>

                        <!-- INPUT NUMERO DE CEDULA -->
                        <div class="contedorNombreUsuario has-validation col-md-6 mb-3">

                            <label for="numeroCedula" class="form-label">Número De Cedula*</label>
                            <input readonly type="text" name="numeroCedula" id="numeroCedula" class="form-control" aria-describedby="emailHelp" placeholder="Numero de cedula" value="<?php if (isset($_REQUEST['cedula'])) echo $_REQUEST['cedula'] ?>" required>

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa un Número de cedula.
                            </div>

                        </div>

                        <!-- INPUT NUMERO DE CELULAR -->
                        <div class="contedorNumeroCelular has-validation col-md-6 mb-3">

                            <label for="numeroCelular" class="form-label">Número de celular*</label>
                            <input type="number" name="numeroCelular" id="numeroCelular" class="form-control" aria-describedby="emailHelp" required>

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa un Número telefonico.
                            </div>

                        </div>

                        <!-- INPUT FECHA DE NACIMIENTO -->
                        <div class="contedorFechaNacimiento has-validation col-md-6 mb-3">

                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento*</label>
                            <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control" aria-describedby="emailHelp" required>

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa una fecha de nacimiento.
                            </div>

                        </div>

                        <!-- INPUT LUGAR DONDE VIVE-->
                        <div class="contedorFechaNacimiento has-validation col-md-6 mb-3">

                            <label for="lugar_donde_vive" class="form-label">Lugar donde vive*</label>
                            <input type="text" name="lugar_donde_vive" id="fechaNacimiento" class="form-control" placeholder="Jipijapa-Manbi-Ecuador" aria-describedby="emailHelp" required>

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa un lugar.
                            </div>

                        </div>

                        <!-- INPUT CARRERA-->
                        <div class="has-validation col-md-6 mb-3">
                            <label for="seleccion-carrera" class="form-label">Carrera*</label>
                            <select name="seleccion-carrera" id="seleccion-carrera" class="form-select" aria-label="Default select example" required>
                                <option selected disabled value="">Seleciona una Carrera</option>
                                <?php

                                foreach ($datosCarrera as $e) {
                                ?>
                                    <option value="<?php echo $e['id_carrera'] ?>"><?php echo $e['nombre_carrera'] ?></option>
                                <?php
                                }


                                ?>
                            </select>

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, selecciona una carrera.
                            </div>
                        </div>

                        <!-- INPUT ENVIAR -->
                        <div class="contenedorEnviar has-validation col-md-6 mb-3">
                            <input type="submit" name="enviar" class="form-control botonEnviar" value="Registrar">
                        </div>

                    </div>
                </div>
            </form>


        </section>


    </main>


    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>


    <!-- HEADER DORPDOWN -->
    <script>
        (function($bs) {
            const CLASS_NAME = 'has-child-dropdown-show';
            $bs.Dropdown.prototype.toggle = function(_orginal) {
                return function() {
                    document.querySelectorAll('.' + CLASS_NAME).forEach(function(e) {
                        e.classList.remove(CLASS_NAME);
                    });
                    let dd = this._element.closest('.dropdown').parentNode.closest('.dropdown');
                    for (; dd && dd !== document; dd = dd.parentNode.closest('.dropdown')) {
                        dd.classList.add(CLASS_NAME);
                    }
                    return _orginal.call(this);
                }
            }($bs.Dropdown.prototype.toggle);

            document.querySelectorAll('.dropdown').forEach(function(dd) {
                dd.addEventListener('hide.bs.dropdown', function(e) {
                    if (this.classList.contains(CLASS_NAME)) {
                        this.classList.remove(CLASS_NAME);
                        e.preventDefault();
                    }
                    if (e.clickEvent && e.clickEvent.composedPath().some(el => el.classList && el.classList.contains('dropdown-toggle'))) {
                        e.preventDefault();
                    }
                    e.stopPropagation(); // do not need pop in multi level mode
                });
            });

            // for hover
            function getDropdown(element) {
                return $bs.Dropdown.getInstance(element) || new $bs.Dropdown(element);
            }

            document.querySelectorAll('.dropdown-hover, .dropdown-hover-all .dropdown').forEach(function(dd) {
                dd.addEventListener('mouseenter', function(e) {
                    let toggle = e.target.querySelector(':scope>[data-bs-toggle="dropdown"]');
                    if (!toggle.classList.contains('show')) {
                        getDropdown(toggle).toggle();
                    }
                });
                dd.addEventListener('mouseleave', function(e) {
                    let toggle = e.target.querySelector(':scope>[data-bs-toggle="dropdown"]');
                    if (toggle.classList.contains('show')) {
                        getDropdown(toggle).toggle();
                    }
                });
            });
        })(bootstrap);
    </script>


    <!-- LIBRERIA ANIMACION -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <!-- Validar formulario -->
    <script src="../../../LOGIN/scriptValidarFormulario.js"></script>

    <!-- EVITAR EL REENVIO DE FORMULARIOS -->
    <script src="../../../evitarReenvioFormulario.js"></script>

    <!-- alerta personalziada -->
    <script src="./alertaPersonalizada.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>


    <script>
        AOS.init();


        // LOGICA PARA LA IMAGEN
        const mostrarImagen = document.getElementById('mostrarImagen')
        const inputImagen = document.getElementById('inputImagen')
        const formulario = document.getElementById('formulario')


        inputImagen.addEventListener('change', function(e) {

            let archivo = e.target.files[0]

            let tamañoArchivo = archivo.size / 1000 //se tranforma en kb

            // tamaño maximo de la imagen es 100kb
            if (tamañoArchivo > 100) {
                alertaPersonalizada('ERROR', `Imagen muy pesada. (${tamañoArchivo} kb)`, 'error', 'Regresar', 'no', 'Ingrese <a target="_blank" href="https://squoosh.app">Aquí</a> para bajarle el peso a su imagen (<i>Tamaño recomendado "50kb"</i>)')
                inputImagen.value = ''
                return
            }


            let lector = new FileReader()

            lector.onload = function(evento) {
                mostrarImagen.src = evento.target.result
            }

            lector.readAsDataURL(archivo)
        })

        //cuando se haga submit en el formulario
        formulario.addEventListener('submit', function(e) {
            e.preventDefault()

            let FD = new FormData(formulario);

            fetch('queryInsertarRegistro.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(e => {

                    if (e.mensaje !== 'ok') {
                        alertaPersonalizada('ERROR', 'Algo salio mal, intentalo de nuevo', 'error', 'Regresar', 'no')
                        return
                    }


                    if (e.mensaje === 'ok') {
                        alertaPersonalizada('CORRERCTO', 'Regsitrado Correctamente', 'success', 'Regresar', 'si')

                    }
                })
        })
    </script>
</body>

</html>