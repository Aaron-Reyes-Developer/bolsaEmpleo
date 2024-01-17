function confirmacion(e){
    if( confirm('¿Estas seguro que quieres eliminarlo?')){
        return true;
    }else{
        e.preventDefault();
    }
}

let botonEliminar = document.querySelectorAll('.botonEliminar');

for (let i = 0; i < botonEliminar.length; i++) {
    botonEliminar[i].addEventListener('click', confirmacion);    
}