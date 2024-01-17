( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Error',
        text: 'Tu cuenta ha sufrido errores, por favor create una nueva',
        icon: 'error',
        confirmButtonText: 'Regresar' ,
        allowOutsideClick:false,
        allowEscapeKey: false
    })
    
    //condicion ir al login
    if (accept) {
        window.location.href = './login.php'
    }
})()