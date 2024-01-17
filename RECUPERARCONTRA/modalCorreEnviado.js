
( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: 'Correo enviado',
        icon: 'sucsses',
        confirmButtonText: 'Aceptar' ,
        footer: 'Revisa tu correo electronico ( bandeja de entrada / span)',
        allowOutsideClick:false,
        allowEscapeKey: false
    })
    
    //condicion ir al login
    if (accept) {
        window.location.href = '../LOGIN/login.php'
    }
})()