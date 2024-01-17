<?php
session_start();

//verificamos un dato para poder saber si se registro en el anterior registro
if ($_SESSION['id_empresa'] == null) {

    header('Location: ../../../LOGIN/login.php');
}

if (isset($_POST['enviar'])) {


    $id_empresa = $_SESSION['id_empresa'];

    include("../../../conexion.php");

    // VERIFICAR SI LOS DATOS NO VIENEN VACIOS
    if (($_POST['nombreEmpresa'] == "")
        or
        ($_POST['correoContacto'] == "")
        or
        ($_POST['lugar'] == "")
        or
        ($_POST['detalleEmpresa'] == "")
        or
        ($_POST['servicio'] == "")
    ) {

        echo "<script> alert('Ingresa todos los campos') </script>";
        echo "<script> window.location.href = './registro3.php'; </script>";

        die();
    }

    // obtener datos
    $nombreEmpresa = htmlspecialchars($_POST['nombreEmpresa']);
    $correoContacto = htmlspecialchars($_POST['correoContacto']);
    $lugar = htmlspecialchars($_POST['lugar']);
    $servicio = htmlspecialchars($_POST['servicio']);
    $detalleEmpresa = htmlspecialchars($_POST['detalleEmpresa']);



    // ingresar datos empresa
    $queryIngresar = "INSERT INTO datos_empresa (nombre, correo, lugar, servicios_ofrecer, detalle_empresa, fk_id_usuario_empresa) VALUES ('$nombreEmpresa','$correoContacto','$lugar','$servicio','$detalleEmpresa', '$id_empresa')";
    $respuestaIngresar = mysqli_query($conn, $queryIngresar);


    if ($respuestaIngresar) {

        // ENVIAR A EL REGISTRO 3 CON LA ID DEL USUARIO
        header("Location: ../REGISTRO4/registro4.php");
    } else {
        echo mysqli_error($conn);
    }
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../imagenes/iconos/iconoAdmin/iconoPaginas.gif">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- ANIMACION LIBRERIA -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="estiloregistro3.css">
    <title>Registro</title>
</head>

<body class="body">

    <header class="header">
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../../../index.html">
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


                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Facultades
                            </a>

                            <ul class="dropdown-menu">

                                <!-- Ciencias de la salud -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Ciencias de la Salud
                                    </a>
                                    <ul class="dropdown-menu" style="background-color: #274546; color: #fff;">

                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/enfermeria/" style="color: #fff;">Enfermeria</a></li>
                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/laboratorio/" style="color: #fff;">Laboratorio Clinico</a></li>

                                    </ul>
                                </li>

                                <!-- Ciencias Técnicas -->
                                <li class="nav-item dropdown">

                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Ciencias Técnicas
                                    </a>

                                    <ul class="dropdown-menu" style="background-color: #274546; color: #fff;">

                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/ingenieriacivil/" style="color: #fff;">Ingeniería Civil</a></li>
                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/tecnologiadelainformacion/" style="color: #fff;">Tecnologías de <br> la Información</a></li>
                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/educacion/" style="color: #fff;">Educación</a></li>

                                    </ul>
                                </li>

                                <!-- Ciencias Economicas -->
                                <li class="nav-item dropdown">

                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Ciencias Económicas
                                    </a>

                                    <ul class="dropdown-menu" style="background-color: #274546; color: #fff;">

                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/ingenieriacivil/" style="color: #fff;">Administración de <br> Empresas</a></li>
                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/tecnologiadelainformacion/" style="color: #fff;">Contabilidad y <br> Auditoría</a></li>
                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/educacion/" style="color: #fff;">Turismo</a></li>

                                    </ul>
                                </li>

                                <!-- Ciencias Naturales y de la Agricultura -->
                                <li class="nav-item dropdown">

                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Ciencias Naturales <br> y de la Agricultura
                                    </a>

                                    <ul class="dropdown-menu" style="background-color: #274546; color: #fff;">

                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/ingenieriacivil/" style="color: #fff;">Agropecuaria</a></li>
                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/tecnologiadelainformacion/" style="color: #fff;">Ingeniería Ambiental</a></li>
                                        <li><a class="dropdown-item subItem" href="https://unesum.edu.ec/educacion/" style="color: #fff;">Ingeniería Forestal</a></li>

                                    </ul>
                                </li>

                            </ul>


                        </li>

                        <li class="nav-item">
                            <a class="nav-link boton-registrar" href="../../../index.html #temas-destacados">Covenios</a>
                        </li>

                    </ul>

                </div>


            </div>

        </nav>
    </header>


    <main class="contenedor-main">

        <section class="seccion-formulario">

            <div class="contenedor-imagen-progreso">
                <img src="../../../imagenes/progresoRegistro3.png" alt="">
            </div>

            <div class="contenedor-formulario">

                <h2>Rellena los datos Básicos de la empresa</h2>


                <form method="post" id="formulario" class="formulario needs-validation" novalidate data-aos="fade-right">

                    <!-- INPUT NOMBRE -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="nombreEmpresa" class="form-label">Nombre de la Empresa*</label>
                        <input type="text" name="nombreEmpresa" class="form-control" id="nombreEmpresa" aria-describedby="emailHelp" required placeholder="Nombre de la empresa">

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa el nombre de la empresa.
                        </div>

                    </div>

                    <!-- INPUT CORREO DE CONTACTO -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="correoContacto" class="form-label">Correo de contacto*</label>
                        <input type="email" name="correoContacto" class="form-control" id="correoContacto" aria-describedby="emailHelp" required placeholder="nombre@ejemplo.com">

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa el correo de contacto.
                        </div>

                    </div>

                    <!-- INPUT LUGAR EMPRESA -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="lugar" class="form-label">Lugar*</label>
                        <input type="text" name="lugar" class="form-control" id="lugar" aria-describedby="emailHelp" required placeholder="Ej: Jipijapa-Manabi">

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa el lugar de la empresa.
                        </div>

                    </div>

                    <!-- INPUT DETALLE EMPRESA -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="detalleEmpresa" class="form-label">Detalle de la Empresa*</label>
                        <textarea class="form-control" name="detalleEmpresa" id="detalleEmpresa" cols="30" rows="5" required placeholder="Ej: Estamos didacados a la creacion de Aplicaciones web donde disponesmo de 5 sucurcasel..."></textarea>

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa el detalle de la empresa.
                        </div>

                    </div>

                    <!-- INPUT SERVICIO EMPRESA -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="servicio" class="form-label">Servicios que ofrecen*</label>
                        <textarea class="form-control" name="servicio" id="servicio" cols="30" rows="5" required placeholder="..." re></textarea>

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa el servicio que ofrece de la empresa.
                        </div>

                    </div>

                    <input type="submit" name="enviar" class="btn btn-primary boton-enviar-login" value="Continuar">
                </form>


            </div>

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


    <!-- INICIAR LA ANIMACION -->
    <script>
        AOS.init();


        // evitar que el formulario se envie con datos vacio
        const formulario = document.getElementById('formulario');


        // Agrega un evento de escucha para verificar el valor cuando se cambie
        formulario.addEventListener('submit', (e) => {


            var nombreEmpresa = document.getElementById('nombreEmpresa').value;
            var correoContacto = document.getElementById('correoContacto').value;
            var lugar = document.getElementById('lugar').value;
            var detalleEmpresa = document.getElementById('detalleEmpresa').value;
            var servicio = document.getElementById('servicio').value;

            // Verificar si el campo contiene solo espacios en blanco
            if (nombreEmpresa.trim() === '') {

                alert('El campo Nombre Empresa no puede estar en blanco');
                e.preventDefault();

            } else if (correoContacto.trim() === '') {

                alert('El campo Correo de contacto no puede estar en blanco.');
                e.preventDefault();

            } else if (lugar.trim() === '') {

                alert('El campo Lugar no puede estar en blanco.');
                e.preventDefault();

            }
            if (detalleEmpresa.trim() === '') {

                alert('El campo Detalle no puede estar en blanco.');
                e.preventDefault();

            }
            if (servicio.trim() === '') {

                alert('El campo Servicio no puede estar en blanco.');
                e.preventDefault();

            }

            console.log('---', campo, '---');

        });
    </script>


    <!-- Validar formulario -->
    <script src="../../../LOGIN/scriptValidarFormulario.js"></script>
</body>

</html>