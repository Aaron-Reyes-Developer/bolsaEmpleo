function confirmacion(e){
    if( confirm('¿Estas seguro que quieres hacer esa accion?') ) {
        return true
    }else{
        e.preventDefault()
    }
}