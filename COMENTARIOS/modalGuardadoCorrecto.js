
( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: '¡Gracias por dejar tus comentarios en nuestra pagina!',
        icon: 'sucsses',
        confirmButtonText: 'Aceptar' ,
        allowOutsideClick:false,
        allowEscapeKey: false
    })
    
    //condicion ir al login
    if (accept) {
        window.history.go(-2)
    }
})()