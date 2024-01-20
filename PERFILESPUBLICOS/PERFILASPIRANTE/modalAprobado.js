

( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: 'Aprobado Correctamente',
        icon: 'success',
        footer: 'Ponte en contacto con el aspirante',
        confirmButtonText: 'Regresar' ,
        allowOutsideClick:false
    })
    
})()