<?php


session_start();

if ($_SESSION['entra_admin'] != true || !isset($_SESSION['entra_admin'])) {
    header('Location: ../../index.html');
    die();
}

include("../../conexion.php");


// query que muestra todos los comentarios de los aspirantes sin limite
$queryComentariosAspirantesSinLimite = mysqli_query($conn, "SELECT 
                                                                encon.id_econtre_empleo

                                                                FROM econtre_empleo as encon
                                                                LEFT JOIN usuario_estudiantes as usuEs
                                                                ON usuEs.id_usuEstudiantes = encon.fk_id_usuEstudiantes
                                                                LEFT JOIN datos_estudiantes as datosEs
                                                                ON usuEs.id_usuEstudiantes = datosEs.fk_id_usuEstudiantes
                                                                ORDER BY encon.fecha DESC");
$totalComentarios = mysqli_num_rows($queryComentariosAspirantesSinLimite);



if (empty($_REQUEST['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_REQUEST['pagina'];
}

$limiteConsulta = 7;
$desde = ($pagina - 1) * $limiteConsulta;
$totalPaginas = ceil($totalComentarios / $limiteConsulta);



// query que muestra todos los comentarios de los aspirantes con limite
$queryComentariosAspirantes = mysqli_query($conn, "SELECT 
                                                        encon.id_econtre_empleo, 
                                                        encon.nombreEmpresa, encon.puesto,  
                                                        encon.descipcion, encon.imagen, 
                                                        encon.fecha, 
                                                        usuEs.id_usuEstudiantes,
                                                        datosEs.nombre,
                                                        datosEs.apellido,
                                                        datosEs.imagen_perfil 

                                                    FROM econtre_empleo as encon
                                                    LEFT JOIN usuario_estudiantes as usuEs
                                                    ON usuEs.id_usuEstudiantes = encon.fk_id_usuEstudiantes
                                                    LEFT JOIN datos_estudiantes as datosEs
                                                    ON usuEs.id_usuEstudiantes = datosEs.fk_id_usuEstudiantes
                                                    ORDER BY encon.fecha DESC
                                                    LIMIT $desde, $limiteConsulta");

while (mysqli_next_result($conn)) {;
}




////////////////////////////////////////////////////  ELIMINAR ////////////////////////////////////////////////////
if (isset($_REQUEST['eliminar'])) {

    $id_econtre_empleo = $_REQUEST['eliminar'];

    // consulta para eliminar el comentario
    $queryEliminarComentario =  mysqli_query($conn, "DELETE FROM econtre_empleo WHERE (id_econtre_empleo = '$id_econtre_empleo')");
    if ($queryEliminarComentario) {
        echo "<script> alert('Eliminado') </script>";
        echo "<script>  window.history.go(-1) </script>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../..//imagenes/iconos/iconoAdmin/kitty.gif">


    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <link rel="stylesheet" href="../COMENTARIOS/estiloComentarios.css">
    <title>Comentarios</title>
</head>

<body>

    <header class="p-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link " aria-current="page" href="../COMENTARIOS/comentariosAspirantes.php">Aspirantes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../COMENTARIOS/comentariosEmpresas.php">Empresas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active disabled" href="#">Econtre Empleo (<?php echo $totalComentarios ?>)</a>
            </li>

        </ul>
    </header>

    <main class="container">

        <!-- carta -->
        <?php
        while ($recorrerComentario = mysqli_fetch_array($queryComentariosAspirantes)) {

        ?>

            <div class="contenedorCarta ">

                <div class="avatar"><img src="data:Image/jpg;base64,<?php echo base64_encode($recorrerComentario['imagen_perfil']) ?>" alt=""></div>

                <div class="comentario">

                    <h5><?php echo $recorrerComentario['nombre'] ?></h5>
                    <span><?php echo $recorrerComentario['descipcion'] ?></span>
                    <br>

                    <span> <b>Fecha: </b><?php echo $recorrerComentario['fecha'] ?></span>
                    <br>

                    <span> <b>Empresa: </b><?php echo $recorrerComentario['nombreEmpresa'] ?></span>
                    <br>

                    <span> <b>Puesto: </b><?php echo $recorrerComentario['puesto'] ?></span>
                </div>

                <a href="?eliminar=<?php echo $recorrerComentario['id_econtre_empleo'] ?>" class="btn-close" aria-label="Close"></a>

                <a class="verFoto" href="../mostrarImagenesEmpleo.php?id_imagen=<?php echo $recorrerComentario['id_econtre_empleo'] ?>">Ver foto...</a>
            </div>
            <hr>
        <?php
        }

        ?>


        <!-- PAGINACION -->

        <div class="paginacion">
            <nav aria-label="Page navigation example">
                <ul class="pagination">

                    <?php
                    $i = 0;
                    for ($i; $i < $totalPaginas; $i++) {
                    ?>
                        <li class="page-item <?php if ($i + 1 == $pagina) echo 'active' ?>"><a class="page-link" href="?pagina=<?php echo $i + 1 ?>"><?php echo $i + 1 ?></a></li>
                    <?php
                    }
                    ?>


                    <li class="page-item <?php if ($i <= $pagina) echo 'disabled' ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>




    </main>

    <!-- evitar reevnio de formularios -->
    <script src="../../evitarReenvioFormulario.js"></script>


    <!-- script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>

</html>