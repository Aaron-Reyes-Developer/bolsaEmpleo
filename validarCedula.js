//devuelve true si es correcto y false si no es correcto
function validarCedula(cedula) {

    // si no se introduce los 10 digitos
    if(cedula.length < 10 || cedula.length > 10){
        return false
    }

    cedulaUltimoDigito = Number(cedula.slice(9, 10))
    cedulaNueveDigitos = cedula.slice(0, 9)

    let cedulaSeparada = cedulaNueveDigitos.split("")


    let multiplicacionTotal = []
    let sumaMultiplicacionPar = 0
    let sumaMultiplicacionImpar = 0

    let sumaMultiplicacion = 0

    for (let i = 0; i < cedulaSeparada.length; i++) {

        // multiplica las posiciones pares
        if (i % 2 == 0) {

            multiplicacionTotal[i] = cedulaSeparada[i] * 2

            // si el resultado es mayor que 9 se le resta 9
            if (multiplicacionTotal[i] > 9) {
                multiplicacionTotal[i] = multiplicacionTotal[i] - 9
            }

            sumaMultiplicacionPar += multiplicacionTotal[i];


            // multiplica las posiciones impares
        } else {


            multiplicacionTotal[i] = cedulaSeparada[i] * 1

            // si el resultado es mayor que 9 se le resta 9
            if (multiplicacionTotal[i] > 9) {

                multiplicacionTotal[i] = multiplicacionTotal[i] - 9
            }

            sumaMultiplicacionImpar += multiplicacionTotal[i];

        }


        sumaMultiplicacion += multiplicacionTotal[i]
    }




    // se tranforma en string para poder separa los numeros
    sumaMultiplicacion = String(sumaMultiplicacion)
    let ultimoNumero = Number(sumaMultiplicacion.slice(1, 2))



    // si el ultimo numero es diferente de 0 entones se resta 10 - ultimo numero
    if (ultimoNumero != 0) {
        numeroVerificador = 10 - ultimoNumero
    } else {
        numeroVerificador = ultimoNumero
    }




    // si todo sale bien el ultimo numero debe ser igual que el ultimo numero 
    // de la cedula
    return numeroVerificador == cedulaUltimoDigito ? true : false;
    
}