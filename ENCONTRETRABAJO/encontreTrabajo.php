<?php

include('../conexion.php');

//ocultar los errores cunado se termine el proyecto 
// error_reporting(0);

session_start();
$id_aspirante = $_SESSION['id_aspirantes'];


//si se intenta ingresar sin iniciar sesion
if ($id_aspirante == null) {
    header('Location: ../LOGIN/login.php');
    die();
}


// TODOS LOS DATOS ASPIRANTE
$queryMainAspirante = "call datosMainEstudiante('$id_aspirante')";
$respuestaMainAspirante = mysqli_query($conn, $queryMainAspirante);
$recorrerMainAspirante = mysqli_fetch_array($respuestaMainAspirante);
while (mysqli_next_result($conn)) {;
}

//nombre de las empresas
$queryNombreEmpresa = mysqli_query($conn, "SELECT id_datos_empresa, nombre FROM datos_empresa");
$datosNombresEmpresas = array();
while ($recorrerNombreEmpresa = mysqli_fetch_array($queryNombreEmpresa)) {
    $datosNombresEmpresas[] = $recorrerNombreEmpresa;
}

// GUARDAR DATOS DE ENCONTRE EMPLEO
if (isset($_POST['enviar'])) {


    // verificar si los datos no viene vacios
    if (
        $_POST['descripcion'] == "" ||
        $_POST['nombreEmpresa'] == "" ||
        $_POST['puestoDeTrabajo'] == "" ||
        $_FILES['imagen']['tmp_name'] == null
    ) {
        echo "Datos vacios";
        die();
    }


    // formatos permitidos en imagenes
    $permitidos = array('image/jpg', 'image/png', 'image/gif', 'image/jpeg', 'image/jfif');
    $limite_kb = 100;


    // imagen
    if (in_array($_FILES['imagen']['type'], $permitidos) && $_FILES['imagen']['size'] <= $limite_kb * 1024) {

        $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    } else {
?>

        <section>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src='./errorImagen.js'></script>

        </section>

    <?php
        die();
    }



    // OBTENER DATOS
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $nombreEmpresa = htmlspecialchars($_POST['nombreEmpresa']);
    $puestoDeTrabajo = htmlspecialchars($_POST['puestoDeTrabajo']);



    $queryInsertar = mysqli_query($conn, "INSERT INTO econtre_empleo (nombreEmpresa, puesto,descipcion, imagen, fk_id_usuEstudiantes) VALUES ('$nombreEmpresa','$puestoDeTrabajo','$descripcion', '$imagen', '$id_aspirante') ");

    if ($queryInsertar) {

    ?>

        <section>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src='./modalCorrectoTrabajo.js'></script>

        </section>
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


    <link rel="stylesheet" href="estiloEncontreTrabajo.css">
    <title>Encontré Trabajo</title>
</head>

<body>

    <header class="header">
        <nav class="navbar navbar-expand-lg lg-white">

            <div class="container-fluid ">

                <!-- <a class="navbar-brand" href="../index.html">
                    <img src="../imagenes/logoUnesum.png" alt="">
                </a> -->

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                    <ul class="navbar-nav">

                        <li class="nav-item iconoLisa ">
                            <a class="nav-link iconoEnlace" aria-current="page" href="../PERFILASPIRANTE/INICIO/inicio.php"><img src="../imagenes/Iconos/casa.svg" alt=""></a>
                        </li>


                        <li class="nav-item iconoLisa">
                            <a class="nav-link iconoEnlace" aria-current="page" href="#"><img src="../imagenes/Iconos/maleta.svg" alt=""></a>
                        </li>

                        <li class="nav-item iconoLisa">
                            <!-- Mandar un dato al cerrarSesion para que tenga acceso -->
                            <?php $_SESSION['ok'] = "ok" ?>


                            <a class="nav-link iconoEnlace" aria-current="page" href="../cerrarSesion.php"><img src="../imagenes/Iconos/salir.svg" alt=""></a>
                        </li>

                        <li class="nav-item lista-avatar-nav">
                            <a class="nav-link enlace-avatar" aria-current="page" href="../PERFILASPIRANTE/perfilAspirante.php"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerMainAspirante['imagen_perfil']) ?>" alt=""></a>
                        </li>



                    </ul>

                </div>


            </div>

        </nav>
    </header>


    <main class="main ">

        <div class="contenedorSecciones">

            <!-- SECCION BIENVENIDO -->
            <section class="seccionBienvenido" data-aos="fade-right">

                <div class="contenedorBienvenido">
                    <h1>Bienvenido</h1>
                    <span>a la sección <b>Encontré Empleo</b></span>
                </div>


                <div class="parrafoBienvenido">
                    <p>¡Felicidades por encontrar empleo! Nos alegra mucho que hayas obtenido una nueva oportunidad laboral. Tu éxito es importante para nosotros y nos gustaría pedirte un favor. Por favor, tómate un momento para completar el siguiente formulario de confirmación de empleo, para que podamos actualizar nuestros registros y celebrar contigo. ¡Gracias por tu colaboración y enhorabuena por tu nuevo trabajo!</p>
                </div>

                <!-- FLECHA -->
                <a href="#formulario">
                    <svg class="flecha" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z" />
                    </svg>
                </a>
            </section>

            <!-- SECCION FORMULARIO -->
            <section class="seccionFormulario" data-aos="fade-left" id="formulario">

                <form action="" method="post" class="formulario" enctype="multipart/form-data">

                    <h2>¡Completa el formulario!</h2>

                    <!-- NOMBRE DE LA EMPRESA -->
                    <div class="mb-3">
                        <select name="nombreEmpresa" id="" class="form-select" required>

                            <option disabled selected value="">Nombre de la empresa*</option>

                            <?php
                            foreach ($datosNombresEmpresas as $e) {
                            ?>
                                <option value="<?php echo $e['nombre'] ?>"><?php echo $e['nombre'] ?></option>
                            <?php
                            }

                            ?>

                        </select>
                    </div>

                    <!-- PUESTO -->
                    <div class="mb-3">
                        <label for="puestoDeTrabajo" class="form-label">Puesto de Trabajo*</label>
                        <input type="text" id="puestoDeTrabajo" name="puestoDeTrabajo" class="form-control" placeholder="Añade tu puesto de trabajo" required>
                    </div>


                    <!-- DETALLE DE EMPLEO -->
                    <div class="mb-3 ">
                        <label for="texto" class="form-label">Detalle de tu empleo*</label>
                        <textarea type="text" name="descripcion" class="form-control" id="texto" placeholder="Ej: Gracias a la bolsa de empleo Unesum pude encontrar tabajo en x empresa donde tengo un puesto x" required style="height: 200px"></textarea>
                    </div>

                    <!-- FOTO DE EMPLEO -->
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Sube una imagen de tu trabajo*</label>
                        <input class="form-control" name="imagen" type="file" id="formFile" required>
                    </div>

                    <div class="mb-3">
                        <input class="form-control boton" name="enviar" type="submit" value="Enviar">
                    </div>
                </form>


            </section>


        </div>




    </main>




    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- VALIDAR FORMULARIO VACIOS -->
    <script src="./validarFormulariosVacios.js"></script>


    <!-- script alertas -->


    <!-- JS LIBRERIA ANIMACIONES -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>


    <!-- evitar el reenvio de formularios -->
    <script src="../evitarReenvioFormulario.js"></script>


    <!-- error imagen -->



    <!-- SCRIPT para efecto smooth en las etiquetas <a> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add smooth scrolling to all links
            $("a").on('click', function(event) {

                // Make sure this.hash has a value before overriding default behavior
                if (this.hash !== "") {
                    // Prevent default anchor click behavior
                    event.preventDefault();

                    // Store hash
                    var hash = this.hash;

                    // Using jQuery's animate() method to add smooth page scroll
                    // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 400, function() {

                        // Add hash (#) to URL when done scrolling (default click behavior)
                        window.location.hash = hash;
                    });
                } // End if
            });
        });
    </script>


</body>

</html>