<?php

// edad con el parametro de la fecha de nacimiento
function calcularEdad($fechaNacimiento)
{
    // Crear un objeto DateTime para la fecha actual
    $fechaActual = new DateTime();

    // Crear un objeto DateTime para la fecha de nacimiento
    $fechaNacimiento = new DateTime($fechaNacimiento);

    // Calcular la diferencia entre las dos fechas
    $diferencia = $fechaActual->diff($fechaNacimiento);

    // Obtener la edad en años
    $edad = $diferencia->y;

    return $edad;
}


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



// FUNCION PARA RETORNAR UNA CONTRASEÑA TEMPORAL
function funcion_contra_temporal($correo, $contra)
{
}
