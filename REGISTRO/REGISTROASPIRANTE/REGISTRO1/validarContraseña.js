//ESTE MODAL SE EJECUTA AUTOMATICAMENTE CUNADO SE LLAMA A ESTE DOCUMENTO

//esta funcion extrae el parametro 'accept' 
//(cunado se apreta el boton 'regresar') 
//y hace una condicion para ver si se apreto el boton

( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Error',
        text: 'Contraseñas no coinciden',
        icon: 'error',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Regresar' 
    })
    
    //condicion ir al login
    if (accept) {
        window.history.back();
    }
})()

