<?php

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

// como funciona la contraseña temporal:

/*
se obtiene el correo y la contraseña (que esta encriptada) de la base de datos
se une el correo y la contraseña con un hash : hash('ripemd160', correro.contra)
eso hara que se cree una contraseña que se enviara al correo (limitada por 10 caracteres) y esta contraseña sera la temporal
a su vez en la base de datos se actualizara la contraseña temporal que se envio al correo, pero la contraseña tendra que ser encriptada
hash('ripem160', contra_temporal_enviada_al_correo_sin_limitar)
esto hara que la contraseña que se guarda estara encriptada
lo cual para cuando el usuario revise su correo y obtenga la contra temporal y la ponga en el login
esta sera encriptada a hash, dando como contraseña la misma que esta en la base de datos



*/
if (isset($_POST['botonEnviar'])) {

    include('../conexion.php');


    // si los datos no se completan
    if (
        !isset($_POST['correo']) ||
        $_POST['correo'] == "" ||
        !isset($_POST['tipo_usuario']) ||
        $_POST['tipo_usuario'] == ""
    ) {
        echo "<script> alert('Dato vacio') </script>";
        die();
    }



    $correo = htmlspecialchars($_POST['correo']);
    $seleccion = htmlspecialchars($_POST['tipo_usuario']);




    // buscar si el correo existe
    if ($seleccion === "Aspirante") {
        $queryBuscarCorreo = mysqli_query($conn, "SELECT id_usuEstudiantes, correo, contra, contra_temporal FROM usuario_estudiantes WHERE correo = '$correo' AND estado_cuenta = 1 ");
    } else if ($seleccion === "Empresa") {
        $queryBuscarCorreo = mysqli_query($conn, "SELECT id_usuario_empresa, correo, contra, contra_temporal FROM usuario_empresa WHERE correo = '$correo' AND estado_cuenta = 1 ");
    }




    // si no existe el correo en la base de datos
    if (mysqli_num_rows($queryBuscarCorreo) <= 0) {
?>

        <body>

            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalNoExisteCorreo.js"></script>

        </body>
    <?php
        die();
    }




    // datos para enviar al correo
    $recorrerCorreo = mysqli_fetch_array($queryBuscarCorreo);
    $contraBd = $recorrerCorreo['contra'];
    $contra_temporal_enviada_correo = Limitar_cadena(hash('ripemd160', $correo . $contraBd), 10, '');




    // enviar correo
    $para = $correo;
    $titulo = "Contraseña Temporal";
    $mensaje = "Esta sera tu contraseña temporal:" . "\r\n" . $contra_temporal_enviada_correo . "\r\n" . "Usala para iniciar sesion y posteriormente cambiar tu contraseña";
    $miCorreo = "From: soporte@trabajounesum.com";




    // si todo sale bien al enviar el correo (se actualiza la contraseña de la cuenta)
    if (mail($para, $titulo, $mensaje, $miCorreo)) {


        // una vez que se obtiene los datos se actualiza la contraseña temporal para que siempre sea una nueva cada vez que se cambia la contraseña
        // contraseña encriptada porque en el login tambien se incripta al momento de colorcar la contraseña
        $contra_temporal_enviada_correo = hash('ripemd160', $contra_temporal_enviada_correo);


        // actualizar la contraseña temporal
        if ($seleccion === "Aspirante") {
            $queryEditarContra_temporal = mysqli_query($conn, "UPDATE usuario_estudiantes SET contra_temporal = '$contra_temporal_enviada_correo' WHERE correo = '$correo' ");
        } else if ($seleccion === "Empresa") {
            $queryEditarContra_temporal = mysqli_query($conn, "UPDATE usuario_empresa SET contra_temporal = '$contra_temporal_enviada_correo' WHERE correo = '$correo' ");
        }


    ?>

        <body>

            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalCorreEnviado.js"></script>

        </body>



    <?php
    } else {
    ?>

        <body>


            <!-- MODAL -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src="./modalErrorCorreo.js"></script>

        </body>
<?php
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imagenes/iconos/iconoAdmin/iconoPaginas.gif">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <link rel="stylesheet" href="estiloRecuperarCorreo.css">
    <title>Recupera tu contraseña</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../index.html">
                    <img src="../imagenes/logoUnesum.webp" alt="Unesum">
                </a> -->

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

        <section class="seccionPortada" data-aos="fade-right">
            <img src="../imagenes/portadaCorreo.png" alt="">
        </section>


        <section class="seccionFormulario" data-aos="fade-left">

            <form action="" method="post" class="formulario">

                <div class="mb-5">
                    <h1>Recupera tu contraseña</h1>
                </div>

                <select class="form-select mb-3" name="tipo_usuario" aria-label="Default select example" required>
                    <option selected value="" disabled>Tipo de usuario</option>
                    <option value="Aspirante">Aspirante</option>
                    <option value="Empresa">Empresa</option>
                </select>


                <div class="mb-3">
                    <label for="inputPassword5" class="form-label">Correo Electronico*</label>
                    <input type="email" id="inputPassword5" name="correo" class="form-control" aria-labelledby="passwordHelpBlock" placeholder="Ingresa tu correo electronico de la bolsa de empleo" required>
                    <div id="passwordHelpBlock" class="form-text">
                        Se le enviara un correo electrónico con una contraseña temporal, esa contraseña la pondrá al iniciar sesión para posteriormente cambiar su contraseña a su gusto
                    </div>
                </div>

                <input type="submit" value="Enviar" name="botonEnviar" class="btn btn-primary botonEnviar">
            </form>

        </section>

    </main>

    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>


    <!-- ANIMACIONES SCRIPT -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <!-- eviar reenvio de formulario -->
    <script src="../evitarReenvioFormulario.js"></script>
</body>

</html>