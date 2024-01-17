<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- ALERTA PERSONALIZAD -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">



    <!-- ANIMACION -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        .body {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #24242c;
        }

        .formulario {
            min-width: 350px;
            max-width: 500px;
            background-color: #fff;
            padding: 20px;
            color: #424242;
            border-radius: 7px;
        }

        .formulario h1 {
            font-size: 1.7rem;
        }

        .formulario p {
            font-size: 0.8rem;
            text-align: center;
        }

        .formulario .botonSubmit {
            background-color: #04ec64;
        }
    </style>

    <title>Validar Codigo</title>
</head>

<body class="body">

    <div class="contenedorFormulario">

        <form class="formulario" id="formulario" data-aos="fade-up">

            <h1>VALIDAR CODIGO EMPRESA</h1>
            <hr>

            <div class="mb-3">
                <label for="codigo" class="form-label">Codigo*</label>
                <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ingrese el codigo">
            </div>

            <p>Si no dispone de un código para su empresa, favor de mandar un correo a <i>insertarCorreo@gmail.com</i> con el nombre y ruc de su empresa.</p>

            <div class="mt-3">
                <input type="submit" class="form-control botonSubmit" id="botonSubmit" value="Validar">
            </div>

        </form>

    </div>

    <!-- BOOSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- ANIMACION -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <!-- ALERTA PERSONALIZADA -->
    <script src="../../../alertaPersonalizada.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>


    <script>
        AOS.init();


        var formulario = document.getElementById('formulario')

        formulario.addEventListener('submit', function(e) {
            e.preventDefault()

            let formdata = new FormData(formulario)

            fetch('./consultaCodigoEmpresa.php', {
                    method: 'POST',
                    body: formdata
                })
                .then(res => res.json())
                .then(e => {

                    // si no esta correcto el codigo
                    if (e.mensaje === 'nop') {
                        alertaPersonalizada('ERROR', 'Codigo incorrecto', 'error', 'Regresar', 'no')
                        return
                    }

                    if (e.mensaje === 'ok') {
                        window.location.href = '../REGISTRO2/registro2.php'

                    }
                })
        })
    </script>


</body>

</html>