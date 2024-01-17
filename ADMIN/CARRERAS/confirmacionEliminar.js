function confirmacion(e){
    if( confirm('¿Estas seguro que quieres hacerlo?') ) {
        return true;
    }else{
        e.preventDefault();
    }
}