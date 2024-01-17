//ESTE MODAL SE EJECUTA AUTOMATICAMENTE CUNADO SE LLAMA A ESTE DOCUMENTO

//esta funcion extrae el parametro 'accept' 
//(cunado se apreta el boton 'ir al login') 
//y hace una condicion para ver si se apreto el boton

( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Incorrecto',
        text: 'No se encontro el registro o cedula ya utilizada en la pagina',
        icon: 'error',
        confirmButtonText: 'Regresar' 
    })
    
    //condicion ir al login
    if (accept) {
        window.location.href = './validacionCedula.php'
    }
})()