//ESTE MODAL SE EJECUTA AUTOMATICAMENTE CUNADO SE LLAMA A ESTE DOCUMENTO

//esta funcion extrae el parametro 'accept' 
//(cunado se apreta el boton 'ir al login') 
//y hace una condicion para ver si se apreto el boton y mandralo al login

( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Todo Correcto!',
        text: 'Se registro correctamente tu perfil',
        icon: 'success',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Ir a Login' 
    })
    
    //condicion ir al login
    if (accept) {
        window.location.href = '../../../LOGIN/login.php'
    }
})()

