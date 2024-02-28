<?php

// error_reporting(0);

include('https://trabajounesum.com/LOGIN/validarLogin.php');

//Obtener los valores para usarlos en el 'value' de los inputs
if (isset($_POST['submit'])) {
    $seleccion = htmlspecialchars($_POST['seleccion']);
    $email = htmlspecialchars($_POST['email']);
    $contra = htmlspecialchars($_POST['contra']);
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imagenes/iconos/iconoAdmin/iconoPaginas.gif">

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


    <!-- ANIMACION LIBRERIA -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>

<body class="body">

    <header>
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <a class="navbar-brand" href="../index.html">
                    <img src="../imagenes/logoUnesum.webp" alt="Unesum">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav ">

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.html">Inicio</a>
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
                            <a class="nav-link boton-registrar" href="../REGISTRO/registro.php">Registrate</a>
                        </li>

                    </ul>

                </div>


            </div>

        </nav>
    </header>


    <main class="main">

        <section class="seccion-login-input container" data-aos="zoom-in-right">

            <form action="./validarLogin.php" method="post" class="formulario needs-validation" novalidate>

                <div class="contenedor-titulo">
                    <h2>Inicio de sesión</h2>
                    <span>Esto es un texto de relleno, cambiarlo</span>
                </div>

                <!-- SELECCION -->
                <div>

                    <select name="seleccion" class="form-select" aria-label="Default select example" required>
                        <option selected disabled value="">Seleciona una opcion de ingreso</option>
                        <option value="empresa">Empresa</option>
                        <option value="aspirante">Aspirante</option>

                    </select>

                    <!-- validar fromulario boostrap -->
                    <div class="invalid-feedback">
                        Por favor, selecciona un tipo de ingreso.
                    </div>
                </div>

                <!-- LINEAS -->
                <div class="contenedor-lineas">
                    <div class="linea-izquierda lineas"></div>
                    <span>Ingresa con tu cuenta</span>
                    <div class="linea-derecha lineas"></div>
                </div>


                <div class="contenedor-correo-contraseña">

                    <!-- INPUT CORREO -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="exampleInputEmail1" class="form-label">Correo*</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="ejemplo@gmail.com" value="<?php if (isset($email)) echo $email ?>">

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa tu correo electrónico.
                        </div>

                    </div>

                    <!-- INPUT CONTRASEÑA -->
                    <div class="mb-3 contenedor-contraseña">

                        <img id="bloqMayusActivado" src="../imagenes/Iconos/letra-mayusculas.png" title="Icono sacado de www.flaticon.es" />

                        <label for="clave" class="form-label">Contraseña*</label>

                        <input type="password" name="contra" class="form-control" id="clave" required value="<?php if (isset($contra)) echo $contra ?>">

                        <div class="invalid-feedback">
                            Por favor, ingresa tu contraseña.
                        </div>

                    </div>

                </div>

                <a href="../RECUPERARCONTRA/recuperarContra.php" class="perdi-contraseña">¿Perdiste tu Contraseña?</a>
                <a href="../REGISTRO/registro.php" class="registrarme">Si no tienes una cuenta, Registrate</a>

                <input type="submit" name="submit" class="btn btn-primary boton-enviar-login" value="Iniciar Sesión" />

            </form>

        </section>


        <section class="seccion-portada-login" data-aos="fade-left">

            <div class="contenedor-imagen-logo">

                <div class="imagen1"><img src="../imagenes/cambiarWiler.jpg" alt=""></div>
                <div class="imagen2"><img src="../imagenes/cambiarWiler.png" alt=""></div>

            </div>


            <div class="contenedor-texto-login">
                <h1>OBTEN MAS PROBABILIDADES DE <br> ENCONTRAR UN TRABAJO</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae inventore rem sapiente </p>
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

    <!-- VALIDAR FORMULARIO -->
    <script src="https://trabajounesum.com/LOGIN/scriptValidarFormulario.js"></script>

    <!-- LIBRERIA ANIMACION -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- INICIAR LA ANIMACION -->
    <script>
        AOS.init();
    </script>


    <!-- SCRIPT PARA DETECTAR LA MAYUSCULA ACTIVADA -->
    <script>
        let miInput = document.getElementById('clave')
        document.getElementById('bloqMayusActivado').style.visibility = 'hidden'
        miInput.addEventListener('keyup', function(event) {
            if (event.getModifierState('CapsLock')) {
                document.getElementById('bloqMayusActivado').style.visibility = 'visible'
            } else {
                document.getElementById('bloqMayusActivado').style.visibility = 'hidden'
            }
        });
    </script>
</body>

</html>