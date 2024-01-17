( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Error',
        text: 'No se encontro el registro',
        icon: 'question',
        confirmButtonText: 'Regresar' ,
        allowOutsideClick:false,
        allowEscapeKey: false
    })
    
    //condicion ir al login
    if (accept) {
        window.location.href = './login.php'
    }
})()