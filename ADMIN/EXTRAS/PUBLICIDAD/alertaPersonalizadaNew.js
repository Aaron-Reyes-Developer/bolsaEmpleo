function alertaPersonalizada(titulo, texto, icono, textoBoton, regresar){
    Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        confirmButtonText: textoBoton,
        allowEscapeKey:false,
        allowEnterKey:true,
        allowOutsideClick:false,
        color: '#fff'
    }).then( e =>{
        if(e.isConfirmed && regresar == 'si'){
            window.location.reload();
        }
    })
};

