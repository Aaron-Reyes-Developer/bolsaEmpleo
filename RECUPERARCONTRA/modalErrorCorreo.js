( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Error',
        text: 'El correo no se pudo enviar',
        icon: 'error',
        confirmButtonText: 'Regresar' ,
        footer: 'Ponte en contacto con: bolsadeempleounesum@gmail.com',
        allowOutsideClick:false,
        allowEscapeKey: false
    })
    
    //condicion ir al login
    if (accept) {
        window.location.href = '../LOGIN/login.php'
    }
})()