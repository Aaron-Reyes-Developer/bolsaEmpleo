( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Error',
        text: 'El correo no existe',
        icon: 'error',
        confirmButtonText: 'Regresar' ,
        allowOutsideClick:false,
        allowEscapeKey: false
    })
    
    //condicion ir al login
    if (accept) {
        window.history.back()
    }
})()