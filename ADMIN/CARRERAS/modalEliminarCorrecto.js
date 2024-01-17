( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: "Accion generada Correctamente",
        icon: 'success',
        confirmButtonText: "Regresar" ,
        allowOutsideClick:false
    })
    
    
    if (accept) {
        window.history.back();
    }
})()