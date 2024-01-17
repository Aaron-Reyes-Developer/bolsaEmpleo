<?php
// oscultamos los errores de el session
// error_reporting(0);


// si no esta el sesion no se ejecuta este codigo
session_start();

// obtenemos el dato del registro anterior (primer registro)
$seleccion_registro = $_SESSION['seleccion-registro'];


// si intetan ingresar con la URL sin pasar por el primer registro
if ($seleccion_registro == null) {

    header('Location: ../../../LOGIN/login.php');
    die();
}

if (isset($_POST['enviar'])) {


    include("../../../conexion.php");

    // VERIFICAR SI LOS DATOS NO VIENEN VACIOS
    if (($_POST['correo'] == "") or ($_POST['contra1'] == "") or ($_POST['contra2'] == "")) {
        echo "<script> alert('Ingresa todos los campos') </script>";

        header('Location: ./registro2.php');
        die();
    }

    // OBTENEMOS LOS DATOS
    $correo = htmlspecialchars($_POST['correo']);
    $contra1 = hash('ripemd160', $_POST['contra1']);
    $contra2 = hash('ripemd160', $_POST['contra2']);


    // consulta para saber si el correo no esta repetido
    $queryCorreosDuplicados = mysqli_query($conn, "SELECT * FROM usuario_empresa WHERE correo = '$correo' AND estado_cuenta = 1");
    $correoRepetido = mysqli_num_rows($queryCorreosDuplicados);



    // mandar un mensaje si el correo esta repetido
    if ($correoRepetido >= 1) {
?>

        <body>
            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalCorreoRepetido.js"></script>
        </body>
<?php
        die();
    }

    // SI LAS CONTRESEÑAS NO COINCIDEN
    if ($contra1 != $contra2) {
        echo "<script> alert('CONTRASEÑA NO COINCIDE') </script>";
        echo "<script> window.history.back(); </script>";

        die();
    }


    // Insertar los datos 
    $queryIngresar = "INSERT INTO usuario_empresa (correo, contra) VALUES ('$correo', '$contra2')";
    $respuestaIngresar = mysqli_query($conn, $queryIngresar);

    // si fue corecto la inserción de datos
    if ($queryIngresar) {

        // sacar el id de el usuario empresa
        $queryEmpresaId = "SELECT * FROM usuario_empresa WHERE correo = '$correo' AND contra = '$contra2' ";
        $respuestaEmpresaId = mysqli_query($conn, $queryEmpresaId);
        $recorrerEmpresaId = mysqli_fetch_array($respuestaEmpresaId);

        // mandar el id por SESSION
        $_SESSION['id_empresa'] = $recorrerEmpresaId['id_usuario_empresa'];

        header('Location: ../REGISTRO3/registro3.php');
    }


    echo "<script> alert('Algo salio mal, intentalo de nuevo') </script>";
    echo "<script> window.location.href = './registro2.php'; </script>";
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

    <link rel="stylesheet" href="estiloregistro2.css">
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
                            <a class="nav-link boton-registrar" href="../../../LOGIN/login.php">Iniciar Sesion</a>
                        </li>

                    </ul>

                </div>


            </div>

        </nav>
    </header>


    <main class="contenedor-main">

        <section class="seccion-formulario">

            <div class="contenedor-imagen-progreso">
                <img src="../../../imagenes/progresoRegistro2.png" alt="">
            </div>

            <div class="contenedor-formulario" data-aos="fade-right">

                <!-- action="./logicaRegistro2.php" -->
                <form method="post" class="formulario needs-validation" novalidate>

                    <h2 class="text-center contenedor-titulo">
                        ¡Encuentra a tus aspirantes!
                    </h2>

                    <!-- CORREO -->
                    <div class="form-floating mb-3 has-validation">
                        <input type="email" name="correo" class="form-control" aria-describedby="emailHelp" id="floatingInput" placeholder="nombre@ejemplo.com" required>
                        <label for="floatingInput">Correo electronico*</label>

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa un correo
                        </div>
                    </div>

                    <!-- CONTRASEÑA -->
                    <div class="form-floating mb-3 has-validation">
                        <input minlength="6" maxlength="12" type="password" name="contra1" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
                        <label for="floatingPassword">Contraseña (6 o más caracteres)* </label>

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa una contraseña mas larga
                        </div>
                    </div>

                    <!-- REPETIR CONTRASEÑA -->
                    <div class="form-floating has-validation">
                        <input minlength="6" maxlength="12" type="password" name="contra2" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
                        <label for="floatingPassword">Repita la contraseña* </label>

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            La contraseña no coincide
                        </div>
                    </div>

                    <!-- TERMINOS Y CONDICIONES -->
                    <div class="text-center contenedor-terminos">
                        <span>Al hacer clic en «Continuar», aceptas las <a href="#" data-bs-toggle="modal" data-bs-target="#exampleCondiciones">Condiciones de uso</a> y la <a href="#" data-bs-toggle="modal" data-bs-target="#exampleTerminos">Política de privacidad</a> de Unesum.</span>
                    </div>

                    <!-- BOTON CONTINUAR -->
                    <div class="my-3">
                        <input type="submit" name="enviar" class="btn btn-primary boton-enviar-login" value="Continuar">
                    </div>

                </form>

            </div>


        </section>

    </main>


    <!-- Modal Condiciones de Uso -->
    <div class="modal fade" id="exampleCondiciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Condiciones de Uso</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <ul>
                        <li>
                            Los usuarios deben entender y aceptar las reglas y requisitos para el uso
                            de nuestro sitio web de bolsa de empleo universitaria. Para utilizar nuestros
                            servicios, los usuarios deben cumplir con ciertos criterios de elegibilidad,
                            como ser estudiantes o exalumnos de la universidad. Nos tomamos muy en serio
                            la privacidad de los usuarios y hemos establecido una política de privacidad
                            que explica cómo manejamos los datos personales.
                        </li>

                        <li>
                            El registro de cuentas de usuario se encuentra disponible, y los usuarios son
                            responsables de mantener la seguridad de sus contraseñas y cuentas.
                            os listados de empleo se publican siguiendo ciertas pautas y deben ser precisos
                            y veraces. Los usuarios pueden solicitar empleos a través de nuestro sitio
                            web siguiendo un proceso específico.
                        </li>

                        <li>
                            Es fundamental que los usuarios se comporten de manera apropiada y respetuosa
                            en nuestra plataforma, y no toleramos el acoso, la discriminación o la publicación
                            de contenido inapropiado. Los derechos de propiedad intelectual de la
                            plataforma y los usuarios están protegidos, y se aplican políticas de derechos
                            de autor.
                        </li>

                        <li>
                            En caso de incumplimiento de los términos y condiciones, nos reservamos
                            el derecho de cancelar cuentas de usuario o aplicar suspensiones. También
                            declaramos nuestra limitación de responsabilidad en cuanto a la calidad de
                            los empleos ofrecidos y los candidatos presentados.
                        </li>

                        <li>
                            Los usuarios deben estar al tanto de nuestros procedimientos de resolución
                            de disputas y de los cambios que puedan realizarse en los términos y condiciones.
                            Si decidimos terminar nuestros servicios, explicaremos cómo se manejarán los
                            datos de los usuarios.
                        </li>

                        <li>
                            Para cualquier pregunta o soporte, los usuarios pueden ponerse en contacto con
                            nosotros a través de la información de contacto proporcionada.
                            <i>correoDeContacto@gmail.com</i>
                        </li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Politica de privacidad -->
    <div class="modal fade" id="exampleTerminos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Politica de privacidad</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    "Bolsa de empleo Unesum" tiene como propósito conectar a profesionales de todo el mundo
                    para impulsar su productividad y ayudarles a lograr sus objetivos laborales. Fundamental
                    para esta misión es nuestro compromiso con la transparencia en cuanto a la recolección,
                    uso y compartición de datos relacionados contigo.

                    <br><br>

                    Esta Política de Privacidad entra en vigor cuando empleas nuestros servicios, los
                    cuales se describen a continuación.<br><br>

                    <ul>
                        <li>
                            En ningún momento, el administrador de la bolsa de empleo tendrá acceso a
                            las contraseñas de los usuarios, dado que estas contraseñas se encuentran
                            protegidas mediante un sólido proceso de encriptación que garantiza su
                            confidencialidad.
                        </li>


                        <li>
                            <b>Recopilación de Datos Personales:</b>
                            Recopilamos información personal, como nombre, dirección de correo electrónico,
                            historial académico y profesional, con el propósito de brindar servicios de
                            búsqueda de empleo y oportunidades laborales.
                        </li>


                        <li>
                            <b>Uso de Datos Personales:</b>
                            Los datos personales se utilizan para crear perfiles de usuario,
                            conectar candidatos con empleadores y facilitar la búsqueda de empleo. <br>
                            Los datos también pueden utilizarse para enviar notificaciones sobre ofertas
                            de empleo relevantes y actualizaciones del sitio.
                        </li>


                        <li>
                            <b>Protección de Datos Personales:</b>
                            Implementamos medidas de seguridad para proteger los datos personales de los usuarios,
                            incluido el cifrado de datos y el acceso restringido.
                        </li>

                        <li>
                            <b>Compartir Datos con Terceros:</b>
                            Los datos personales no se compartirán con terceros sin el consentimiento del usuario,
                            excepto cuando sea necesario para facilitar la búsqueda de empleo o cumplir con requisitos legales.
                        </li>
                    </ul>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>


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
    </script>


    <!-- Validar formulario -->
    <script src="../../../LOGIN/scriptValidarFormulario.js"></script>
</body>

</html>