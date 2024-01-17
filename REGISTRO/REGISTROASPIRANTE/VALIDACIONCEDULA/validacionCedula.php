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

    <!-- ALERTA PERSONALIZADA -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">


    <link rel="stylesheet" href="estiloValidacionCedula.css">
    <title>Validacion Cedula</title>
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


    <main class="main">

        <section class="seccionFormulario" data-aos="fade-right">

            <form  class="formulario"  id="formulario">

                <div>
                    <h1>Validar Graduado</h1>
                    <hr>
                </div>

                <div>
                    <input class="form-control" type="number" id="cedula" name="cedula" placeholder="Cedula" required>

                    <span>Ingresa tu cedula para verificar si eres un graduado de la carrera</span><br>
                </div>

                <input type="submit" name="botonCedula" value="Validar" class="btn btn-primary botonCedula">
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

    <!-- script para validar cedula -->
    <script src="../../../validarCedula.js"></script>

    <!-- evitar reevnvio -->
    <script src="../../../evitarReenvioFormulario.js"></script>

    <!-- alerta personalizada -->
    <script src="../../../alertaPersonalizada.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>


    <!-- LOGICA VALIDAR CEDULA -->
    <script>
        const formulario = document.getElementById('formulario')
        const cedula =  document.getElementById('cedula').value
        
        
        
        formulario.addEventListener('submit', function(e){

            e.preventDefault()
            let formdata = new FormData(formulario)

            
            if(!validarCedula(formdata.get('cedula'))){
                alertaPersonalizada('INCORRECTO','Cedula Invalida','error','Regresar','no')
                return
            }

            fetch('./respuestaCedula.php', {
                method: 'POST',
                body: formdata
            })
            .then(res => res.json())
            .then(e=> {

                // si todo sale bien
                if(e.mensaje === 'ok'){
                    location.href= `../REGISTRO1/registroAspirante1.php?cedula=${e.cedula}&nombre=${e.nombre}&apellido=${e.apellido}`
                }else if(e.mensaje === 'Cedula Inexistente/Ocupada'){
                    alertaPersonalizada('ERROR', `${e.mensaje}`, 'question', 'Rergresar','NO')
                    return
                }
            })


            // ../REGISTRO1/registroAspirante1.php?cedula=$cedula&nombre=$nombre&apellido=$apellido
        })
    </script>
</body>

</html>