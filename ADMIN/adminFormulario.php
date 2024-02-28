<?php

    
    session_start();

    if(isset($_POST['entrar'])){
        
        if($_POST['contra'] == " "){
            echo "vacio";
            die();
        }
        
        

        include('../conexion.php');
        $contra = htmlspecialchars($_POST['contra']);
        
        
        
        $resultado = mysqli_query($conn, "SELECT * FROM adminunesum WHERE contra = '$contra' ");
        
        $contarFilas = mysqli_num_rows($resultado);

        if($contarFilas >= 1){

            $_SESSION['entra_admin'] = true;
            header('Location: https://trabajounesum.com/ADMIN/admin.php');

        }
        else{
            echo "nop";
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos.css">
    <title>Admin</title>
</head>
<body class="body">
    

    <form action="" method="post" class="form needs-validation " novalidate>
        
        <div class="has-validation input-group">

            
            <input type="password" name="contra" class="form-control" required>
            <input type="submit" value="entrar" name="entrar" class="boton btn btn-primary">


            <div class="invalid-feedback">
                RELLENA
            </div>
            

            
        </div>

    </form>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://trabajounesum.com/ADMIN/validarForm.js"></script>
    <script src="https://trabajounesum.com/evitarReenvioFormulario.js"></script>
</body>
</html>