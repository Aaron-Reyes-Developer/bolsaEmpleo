//ESTE MODAL SE EJECUTA AUTOMATICAMENTE CUNADO SE LLAMA A ESTE DOCUMENTO

//esta funcion extrae el parametro 'accept' 
//(cunado se apreta el boton 'Regresar') 
//y hace una condicion para ver si se apreto el boton

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