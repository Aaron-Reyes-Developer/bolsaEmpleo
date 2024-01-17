//ESTE MODAL SE EJECUTA AUTOMATICAMENTE CUNADO SE LLAMA A ESTE DOCUMENTO

//esta funcion extrae el parametro 'accept' 
//(cunado se apreta el boton 'Regresar') 
//y hace una condicion para ver si se apreto el boton

( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: 'Postulado Correctamente',
        icon: 'success',
        confirmButtonText: 'Regresar' ,
        allowOutsideClick:false,
        allowEscapeKey: false
    })
    
    //condicion ir al login
    if (accept) {
        window.location.href = './inicio.php'
    }
})()