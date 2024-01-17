( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: 'Elimiaste tu postulacion',
        icon: 'sucsses',
        confirmButtonText: 'Regresar' ,
        allowOutsideClick:false
    })
    
    //condicion ir al login
    if (accept) {
        window.history.back()
    }
})()