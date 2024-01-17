( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Error Imagen',
        text: 'Introdusca una imagen Correcta',
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