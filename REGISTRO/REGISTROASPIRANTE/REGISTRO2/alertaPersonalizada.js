function alertaPersonalizada(titulo, texto, icono, textoBoton, regresar, footer){
    Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        confirmButtonText: textoBoton,
        footer: footer,
        allowEscapeKey:false,
        allowEnterKey:true,
        allowOutsideClick:false,
        color: '#fff'
    }).then( e =>{
        if(e.isConfirmed && regresar == 'si'){
            window.location.reload()
        }
    })
};

