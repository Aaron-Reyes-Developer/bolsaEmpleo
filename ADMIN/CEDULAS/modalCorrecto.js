( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: "Eliminado Correctamente",
        icon: 'sucsses',
        confirmButtonText: "Ok" ,
        allowOutsideClick:false
    })
    
    //condicion ir al login
    if (accept) {
        window.history.back();
    }
})()