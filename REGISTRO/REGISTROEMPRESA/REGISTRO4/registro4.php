<?php
session_start();

if ($_SESSION['id_empresa'] == null) {

    header('Location: ../../../LOGIN/login.php');
} else if (isset($_POST['registrar'])) {

    $id_empresa = $_SESSION['id_empresa'];

    include("../../../conexion.php");

    // SI LOS CAMPOS VIENEN VACIOS
    if (($_POST['nombreUsuario'] == "") or ($_POST['lugarMaps'] == "")) {

        echo "<script> alert('Ingresa todos los campos') </script>";
        echo "<script> window.location.href = './registro4.php'; </script>";

        die();
    }

    //obtener datos
    $nombreUsuario = htmlspecialchars($_POST['nombreUsuario']);
    $lugarMaps = $_POST['lugarMaps'];
    $gerenteGeneral = htmlspecialchars($_POST['gerenteGeneral']);
    $recursoHumano = htmlspecialchars($_POST['recursoHumano']);
    $antiguedadEmpresa = htmlspecialchars($_POST['antiguedadEmpresa']);
    $paginaWeb = htmlspecialchars($_POST['paginaWeb']);



    // seccion imagen, si no se ingresa una imagen quedara una defoult
    $imagen = "";
    $rutaDefoultImgaen = "../../../imagenes/FotoperfilDefectoEmpresa.jpg";

    // saber si se inserto una imagen 
    if ($_FILES['imagen']['tmp_name'] != null) {

        $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    } else {

        //si no se inserta una imagen quedara con una imagen por defecto
        $imagen = addslashes(file_get_contents($rutaDefoultImgaen));
    }


    // INGRESAR LOS DATOS DE LA EMPRESA
    $queryGuadar = "UPDATE datos_empresa SET nombreUsuario = '$nombreUsuario', imagen_perfil = '$imagen', lugarMaps = '$lugarMaps' , gerente_general = '$gerenteGeneral', recursos_humanos = '$recursoHumano', antiguedad_empresa = '$antiguedadEmpresa', pagina_web = '$paginaWeb' WHERE fk_id_usuario_empresa = '$id_empresa' ";
    $resultadoInsertar = mysqli_query($conn, $queryGuadar);


    if ($resultadoInsertar) {

?>

        <!-- Esto creo que es una mala practica pero no encontre otra manera mas facil de hacerlo -->
        <!-- Muestra el modal de 'todo correcto' y este mismo redirecciona a el login -->

        <body>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script src='./modal.js'></script>

        </body>

<?php
        session_destroy();
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

    <!-- ALERTA -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">


    <link rel="stylesheet" href="estiloregistro4.css">
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

            <div class="contenedor-imagen-progreso ">
                <img src="../../../imagenes/progresoRegistro4.png" alt="">
            </div>

            <div class="contenedor-formulario" data-aos="fade-right">

                <h1>¡Ultimo paso!</h1>


                <form id="formulario" method="post" class="formulario needs-validation" enctype="multipart/form-data" novalidate>

                    <!--Avatar-->
                    <div class="contenedor-avatar">

                        <!-- IMAGEN -->
                        <div class="mb-4 contenedorImagenAvatar">
                            <img id="mostrarImagen" src="../../../imagenes/FotoperfilDefectoEmpresa.jpg" class="" alt="example placeholder" style="width: 200px;" />
                        </div>

                        <!-- INGRESAR IMAGEN -->
                        <div class="contenedor-boton-imagen">
                            <input class="form-control boton-imagen" name="imagen" type="file" id="inputImagen" accept="image/*">
                        </div>

                    </div>

                    <!-- INPUT NOMBRE USUARIO -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="nombreUsuario" class="form-label">Nombre de usuario*</label>
                        <input type="text" name="nombreUsuario" class="form-control" id="nombreUsuario" aria-describedby="emailHelp" required placeholder="">

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa el nombre de usuario.
                        </div>

                    </div>

                    <!-- UBICACION MAPS -->
                    <div class="row g-2">
                        <span class="col-12"> Ubicación exacta maps* <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal"> Ejemplo</a> </span>

                        <!-- Ubicacion maps link -->
                        <div class="col-md contenedor-latitud has-validation">

                            <div class="form-floating">

                                <textarea class="form-control texarea" name="lugarMaps" id="floatingInputGrid" rows="3" required></textarea>
                                <label for="floatingInputGrid">Ubicacion Maps</label>

                                <!-- validar fromulario boostrap -->
                                <div class="invalid-feedback">
                                    Por favor, ingresa la ubicacion en maps.
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- INPUT GERENETE GENERAL -->
                    <div class="mb-3 contenedor-correo ">
                        <label for="gerenteGeneral" class="form-label">Gerente General</label>
                        <input type="text" name="gerenteGeneral" class="form-control" id="gerenteGeneral" aria-describedby="emailHelp" placeholder="Ing. Aaron Josue Reyes Carvajal">

                    </div>

                    <!-- INPUT RECURSOS HUMANOS -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="recursosHumano" class="form-label">Recursos Humanos</label>
                        <input type="text" name="recursoHumano" class="form-control" id="recursosHumano" aria-describedby="emailHelp" placeholder="Ing. Luis Alberto Menendez Salazar">

                    </div>

                    <!-- INPUT ANTIGUEDAD -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="antiguedadEmpresa" class="form-label">Antiguedad Empresa*</label>
                        <input type="number" name="antiguedadEmpresa" class="form-control" id="antiguedadEmpresa" aria-describedby="emailHelp" placeholder="Antiguedad en años" required>

                        <!-- validar fromulario boostrap -->
                        <div class="invalid-feedback">
                            Por favor, ingresa la antiguedad.
                        </div>

                    </div>

                    <!-- INPUT LINK PAGINA WEB -->
                    <div class="mb-3 contenedor-correo has-validation">
                        <label for="linkPagina" class="form-label">Pagina Web</label>
                        <input type="text" name="paginaWeb" class="form-control" id="linkPagina" aria-describedby="emailHelp" placeholder="www.ejemplo.com">
                    </div>

                    <!-- BOTON ENVIAR -->
                    <input type="submit" name="registrar" class="btn btn-primary boton-enviar-login" value="Registrarme">
                </form>


            </div>

        </section>

    </main>




    <!-- Modal Ejemplo Maps-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ejemplo Maps (solo computadora)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body  bg-dark">
                    <div id="carouselExample" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="../../../imagenes/EjemploUbicacionMaps/ejemploMaps1.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="../../../imagenes/EjemploUbicacionMaps/ejemploMaps2.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="../../../imagenes/EjemploUbicacionMaps/ejemploMaps3.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="../../../imagenes/EjemploUbicacionMaps/ejemploMaps4.jpg" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
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

    <script src="./alertaPersonalizada.js"></script>

    <script src="../../../evitarReenvioFormulario.js"></script>

    <!-- INICIAR LA ANIMACION -->
    <script>
        AOS.init();



        // EVITAR QUE EL FORMULARIO PONGAN DATOS EN BLANCO
        const formulario = document.getElementById('formulario');
        const mostrarImagen = document.getElementById('mostrarImagen');


        // LOGICA IMAGEN
        var inputImagen = document.getElementById('inputImagen')

        inputImagen.addEventListener('change', function(e) {

            var archivo = e.target.files[0]

            var achivoTamano = (archivo.size) / 1000 //en kb

            if (achivoTamano > 100) {
                alertaPersonalizada('ERROR', 'Imagen muy pesada (hasta 100kb se aceptan)', 'error', 'Regresar', 'no', 'Ingresa <a target="_blank" href="https://squoosh.app">Aquí</a> para bajar el peso a la imagen')
                inputImagen.value = ""
                return
            }

            var lector = new FileReader()

            lector.onload = function(eventoLector) {
                mostrarImagen.src = eventoLector.target.result
            }

            lector.readAsDataURL(archivo)
        })




        // Agrega un evento de escucha para verificar el valor cuando se cambie
        formulario.addEventListener('submit', (e) => {

            // obtener imagen del input
            var imagen = inputImagen.files[0]

            let formdata = new FormData(formulario)

            // eliminamos la imagen para poder capturarla bien
            formdata.delete('imagen')



            // Verificar si el campo contiene solo espacios en blanco
            if (formdata.get('nombreUsuario').trim() === '') {

                alert('El campo Nombre de Usuario no puede estar en blanco');
                e.preventDefault();

            } else if (formdata.get('lugarMaps').trim() === '') {

                alert('El campo Lugar Maps no puede estar en blanco.');
                e.preventDefault();

            } else if (formdata.get('gerenteGeneral').trim() === '') {

                alert('El campo Gerente General no puede estar en blanco.');
                e.preventDefault();

            }
            if (formdata.get('recursoHumano').trim() === '') {

                alert('El campo Recursos Humanos no puede estar en blanco.');
                e.preventDefault();

            }
            if (formdata.get('antiguedadEmpresa').trim() === '') {

                alert('El campo Antiguedad Empresa no puede estar en blanco.');
                e.preventDefault();

            }



        });
    </script>


    <!-- Validar formulario -->
    <script src="../../../LOGIN/scriptValidarFormulario.js"></script>

    <!-- Modal Ejemplo Maps -->
    <script src="./modalEjemploMaps.js"></script>

    <!-- ALERTA -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>


</body>

</html>