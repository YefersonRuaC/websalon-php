document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    buscarPorFecha();
}

function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e) {
        const fechaSeleccionada = e.target.value;

        //Pasamos la fecha por la url de la pagina, para poder leerla con php y filtrar las citas
        window.location = `?fecha=${fechaSeleccionada}`;
    });
}