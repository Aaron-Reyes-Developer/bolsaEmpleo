//ESTE MODAL SE EJECUTA AUTOMATICAMENTE CUNADO SE LLAMA A ESTE DOCUMENTO

//esta funcion extrae el parametro 'accept' 
//(cunado se apreta el boton 'ir al login') 
//y hace una condicion para ver si se apreto el boton

( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: 'Registrado Correctamente',
        icon: 'sucsses',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Ir al Login' 
    })
    
    //condicion ir al login
    if (accept) {
        window.location.href = '../../../LOGIN/login.php'
    }
})()

