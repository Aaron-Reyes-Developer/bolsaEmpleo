( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: 'Registrado Correctamente',
        icon: 'sucsses',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Regresar' 
    })
    
    
    if (accept) {
        window.location.href = '../PERFILASPIRANTE/INICIO/inicio.php'
    }
})()