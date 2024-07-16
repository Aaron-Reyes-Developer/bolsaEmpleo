<?php
session_start();

//si se ingresa por url sin antes pasar pro el por el primer registro
if ($_SESSION['seleccion-registro'] == null || $_SESSION['seleccion-registro'] == "") {
    header("Location: ../../../LOGIN/login.php");
} else {

    // obtener cedula de la url
    $cedula = $_REQUEST['cedula'];
    $nombre = $_REQUEST['nombre'];
    $apellido = $_REQUEST['apellido'];

    //si se apreta el boton enviar
    if (isset($_POST['enviar'])) {

        include('../../../conexion.php');

        //captura de datos
        $correo = $_POST['correo'];
        $contra = hash('ripemd160', htmlspecialchars($_POST['contra']));
        $contra2 = hash('ripemd160', htmlspecialchars($_POST['contra2']));

        if ($contra != $contra2) {
?>

            <body>
                <!-- MODAL -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script src="validarContraseña.js"></script>
            </body>
        <?php
            die();
        }


        //fucion para ingresar los datos a la bd
        function ingresarDatos($correoIngresar, $contraIngresar, $connex)
        {
            $queryIngresar = "INSERT INTO usuario_estudiantes (correo, contra) VALUES ('$correoIngresar', '$contraIngresar')";
            $resultado = mysqli_query($connex, $queryIngresar);
            return $resultado;
        }

        // buscar si ya existe el correo
        $queryExisteCorreo = mysqli_query($conn, "SELECT correo FROM usuario_estudiantes WHERE correo = '$correo' ");

        if (mysqli_num_rows($queryExisteCorreo) >= 1) {
        ?>

            <body>
                <!-- MODAL -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script src="correoYaExiste.js"></script>
            </body>

<?php
            die();
        }


        if (ingresarDatos($correo, $contra2, $conn)) {

            //consultar el id del registro para enviarlo por $_SESSION al otro registro y usarlo para el fk de la tabla
            $queryConsulta = "SELECT id_usuEstudiantes FROM usuario_estudiantes WHERE correo = '$correo' AND contra ='$contra2' ";
            $respuestaConsulta = mysqli_query($conn, $queryConsulta);
            $recorrer = mysqli_fetch_array($respuestaConsulta);


            //enviar el id para verificar en el siguiente registro y usarlo para el fk 
            $_SESSION['id_usuario'] = $recorrer['id_usuEstudiantes'];

            header("Location: ../REGISTRO2/registroAspirante2.php?cedula=$cedula&nombre=$nombre&apellido=$apellido");
        } else {
            echo "algo salio mal";
            echo "<br>", mysqli_error($conn);
            die();
        }
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

    <link rel="stylesheet" href="estiloAspirante1.css">
    <title>Registro Aspirante</title>
</head>

<body>

    <header>
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

            <form method="post" class="formulario needs-validation" novalidate>

                <div class="contenedorFrase mb-4">
                    <h1>¡Registrate para encontrar nuevas oportunidades de trabajo!</h1>
                </div>

                <div class="container">
                    <div class="row contenedor-inputs">

                        <!-- INPUT CORREO -->
                        <div class="contenedorInputs contedorCorreo has-validation col-12 mb-3">

                            <label for="correo" class="form-label">Correo*</label>
                            <input type="email" name="correo" id="correo" class="form-control" required aria-describedby="emailHelp">

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa un correo.
                            </div>

                        </div>

                        <!-- INPUT CONTRASEÑA -->
                        <div class="contenedorInputs contedorNombreUsuario has-validation col-12 mb-3">

                            <label for="contra" class="form-label">Contraseña*</label>
                            <input type="password" minlength="6" name="contra" id="contra" class="form-control" required aria-describedby="emailHelp">

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa una contraseña mas larga.
                            </div>

                        </div>

                        <!-- INPUT REPETIR CONTRASEÑA -->
                        <div class="contenedorInputs contedorNombreUsuario has-validation col-12 mb-3">

                            <label for="contra2" class="form-label">Reptia la Contraseña*</label>
                            <input type="password" minlength="6" name="contra2" id="contra2" class="form-control" required aria-describedby="emailHelp">

                            <!-- validar fromulario boostrap -->
                            <div class="invalid-feedback">
                                Por favor, ingresa una contraseña mas larga.
                            </div>

                        </div>

                        <!-- INPUT ENVIAR -->
                        <div class="contenedorInputs contenedorEnviar col-6 mb-3">

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


    <!-- INICIAR LA ANIMACION -->
    <script>
        AOS.init();
    </script>


    <!-- Validar formulario -->
    <script src="../../../LOGIN/scriptValidarFormulario.js"></script>

    <!-- EVITAR EL REENVIO DE FORMULARIOS -->
    <script src="../../evitarReenvioFormulario.js"></script>
</body>

</html>