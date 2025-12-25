function abrirModalNuevo() {
    document.getElementById('modalNuevoMensaje').style.display = 'block';
}

function cerrarModalNuevo() {
    document.getElementById('modalNuevoMensaje').style.display = 'none';
}

// Cerrar si hacen clic fuera del modal
window.onclick = function(event) {
    let modal = document.getElementById('modalNuevoMensaje');
    if (event.target == modal) {
        cerrarModalNuevo();
    }
}