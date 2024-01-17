//ESTE MODAL SE EJECUTA AUTOMATICAMENTE CUNADO SE LLAMA A ESTE DOCUMENTO

//esta funcion extrae el parametro 'accept' 
//(cunado se apreta el boton 'Aceptar') 
//y hace una condicion para ver si se apreto el boton

( async () => { 

    //modal
    const {value: accept} = await Swal.fire({
        title: 'Correcto',
        text: "Ingresado Correctamente",
        icon: 'sucsses',
        confirmButtonText: "Regresar" ,
        allowOutsideClick:false
    })
    
    //condicion ir al login
    if (accept) {
        window.history.back();
    }
})()




