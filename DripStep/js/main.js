
document.addEventListener("DOMContentLoaded", function() {
    // Confirmación de borrado
    const botonesBorrar = document.querySelectorAll('.btn-danger');
    botonesBorrar.forEach(boton => {
        boton.addEventListener('click', function(e) {
            if (!confirm("¿Seguro que quieres eliminar este elemento?")) {
                e.preventDefault();
            }
        });
    });
});