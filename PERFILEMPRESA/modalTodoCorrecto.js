( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: 'Datos Registrado Correctamente',
        icon: 'success',
        confirmButtonText: 'Aceptar' ,
        allowOutsideClick:false,
        allowEscapeKey: false
    })
    
    //condicion ir al login
    if (accept) {
        window.history.back();
    }
})()