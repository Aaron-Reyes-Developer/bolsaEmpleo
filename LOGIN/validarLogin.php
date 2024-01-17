<?php
include("../conexion.php");

// OCULTAR ERRORES DE VARIABLES INDEFINIDAS POR QUE VIENEN VACIAS
// error_reporting(0);



// CUANDO SE APRETA EL BOTON DE ENTRAR
if (isset($_POST['submit'])) {

    session_start();

    $seleccion = htmlspecialchars($_POST['seleccion']);
    $email = htmlspecialchars($_POST['email']);
    $contra = htmlspecialchars(hash('ripemd160', $_POST['contra']));

    // VALIDAR LOS CAMPOS VACIOS
    if ($seleccion == "") {
        echo "<script> alert('Seleccion Vacia') </script>";
        echo "<script> window.location.href = './login.php'; </script>";
        die();
    }
    if ($email == "") {
        echo "<script> alert('Correo Vacio') </script>";
        echo "<script> window.location.href = './login.php'; </script>";
        die();
    }
    if ($contra == "") {
        echo "<script> alert('Contraseña Vacia') </script>";
        echo "<script> window.location.href = './login.php'; </script>";
        die();
    }



    // CONSULTA PARA SABER SI EL REGISTRO ESTA CORRECTAMENTE CON LOS DATOS RELLENADOS DEL REGISTRO
    if ($seleccion === "aspirante") {

        // consulta para saber si su registro tiene datos (si es que se registro correctamente)
        $queryLoginSinDatos = mysqli_query($conn, "SELECT *	FROM usuario_estudiantes as usuEs 
                                                    WHERE not exists (SELECT * FROM datos_estudiantes as datos WHERE datos.fk_id_usuEstudiantes = usuEs.id_usuEstudiantes) 
                                                    AND usuEs.correo = '$email' AND usuEs.contra = '$contra' ");

        // sacamos el id de el login para posteriormente eliminarlo
        $recorrer = mysqli_fetch_array($queryLoginSinDatos);
        $id_loginSinDatos = $recorrer['id_usuEstudiantes'];
    } else if ($seleccion === "empresa") {

        // consulta para saber si su registro tiene datos (si es que se registro correctamente)
        $queryLoginSinDatos = mysqli_query($conn, "SELECT *	FROM usuario_empresa as usuEm 
                                                    WHERE not exists (SELECT * FROM datos_empresa as datos WHERE datos.fk_id_usuario_empresa = usuEm.id_usuario_empresa) 
                                                    AND usuEm.correo = '$email' AND usuEm.contra = '$contra' ");

        // sacamos el id de el login para posteriormente eliminarlo
        $recorrer = mysqli_fetch_array($queryLoginSinDatos);
        $id_loginSinDatos = $recorrer['id_usuario_empresa'];
    }




    // si existe el correo sin datos mostramos un mensaje y borramos ese correo
    if (mysqli_num_rows($queryLoginSinDatos) >= 1) {

        // borrar registros sin datos correctamente rellenados
        if ($seleccion === "aspirante") {

            // borramos el registro que no tiene datos en aspirante
            $queryBorrarLogin = mysqli_query($conn, "DELETE FROM usuario_estudiantes WHERE (id_usuEstudiantes = '$id_loginSinDatos')");
        } else if ($seleccion === "empresa") {

            // borramos el registro que no tiene dato en empresa
            $queryBorrarLogin = mysqli_query($conn, "DELETE FROM usuario_empresa WHERE (id_usuario_empresa = '$id_loginSinDatos')");
        }


        if ($queryBorrarLogin) {
?>

            <body>
                <!-- boostrap -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

                <!-- MODAL -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script src="./modalErrorCuentaSinDatos.js"></script>

            </body>
        <?php
            die();
        }


        die();
    }



    // Si todo esta bien mandar a la pagina de el perfil o hacer la busqueda etc

    $queryBuscarLogin = "";

    if ($seleccion == "empresa") {

        $queryBuscarLogin = "SELECT * FROM usuario_empresa WHERE correo = '$email' AND contra = '$contra' AND estado_cuenta = '1' ";
    } else if ($seleccion == "aspirante") {

        $queryBuscarLogin = "SELECT * FROM usuario_estudiantes WHERE correo = '$email' AND (contra = '$contra' OR contra_temporal = '$contra') AND estado_cuenta = '1' ";
    }


    $resultado = mysqli_query($conn, $queryBuscarLogin);

    //Si la respuesta es 1 significa que si existe el registro 
    $contarFilas = mysqli_num_rows($resultado);

    // sacar el id de el login
    $id_recorrer = mysqli_fetch_array($resultado);



    if ($seleccion == "aspirante") {

        //sacamos el id de el aspirante
        $id_mostrar = $id_recorrer['id_usuEstudiantes'];

        if ($contarFilas >= 1) {

            //mandar el id para consultar y mostrarlo en el perfil
            $_SESSION['id_aspirantes'] = $id_mostrar;

            // Mandar a otra pagina de perfil
            header('Location: ../PERFILASPIRANTE/perfilAspirante.php');
        } else {

        ?>

            <body>
                <!-- boostrap -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

                <!-- MODAL -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script src="./modalNoSeEncontroRegistro.js"></script>

            </body>
        <?php
            die();
        }
    } else if ($seleccion == "empresa") {

        //sacamos el id de la empresa
        $id_mostrar = $id_recorrer['id_usuario_empresa'];

        if ($contarFilas >= 1) {

            //mandar el id para consultar y mostrarlo en el perfil
            $_SESSION['id_empresa'] = $id_mostrar;

            // Mandar a otra pagina de perfil
            header('Location: ../PERFILEMPRESA/perfilEmpresa.php');
        } else {

        ?>

            <body>

                <!-- boostrap -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

                <!-- MODAL -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script src="./modalNoSeEncontroRegistro.js"></script>

            </body>
<?php
            die();
        }
    }

    die();
}




?>