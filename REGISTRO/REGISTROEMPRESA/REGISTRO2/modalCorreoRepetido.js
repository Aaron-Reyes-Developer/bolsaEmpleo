( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Error',
        text: 'El correo ya existe',
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