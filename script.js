// El addEventListener, Registra un evento a un objeto en especifico, este puede ser un elemento o un archivo.
// document.addEventListener('DOMContentLoaded', ...) se usa para ejecutar una función cuando el documento HTML ha sido completamente cargado y analizado.
document.addEventListener('DOMContentLoaded', () => {
    // Muestra la primera notificación
    mostrarnotificaciones();
    // Cambia la notificación cada 5 segundos
    setInterval(mostrarnotificaciones, 5000);
});

// Lista de notificaciones a mostrar
const notificaciones = [
    '¡Oferta especial en vuelos a París!',
    'Descuento de hasta 40% en vuelos a Nueva York.',
    'Nuevo paquete turístico a Tokio disponible.'
];

let Indicenotificaciones = 0; // Índice de la notificación actual

// Función para mostrar las notificaciones con el uso de getElementById (especificado por ID)
function mostrarnotificaciones() {
    const contenedornotificaciones = document.getElementById('notifications-container');
    contenedornotificaciones.innerText = notificaciones[Indicenotificaciones]; // Actualiza el texto de la notificación
    Indicenotificaciones = (Indicenotificaciones + 1) % notificaciones.length; // Pasa a la siguiente notificación
}

// Función para limpiar los campos de entrada y los resultados con el uso de getElementById (especificado por ID)
//function limpiarinput() {
//    document.getElementById('destination').value = ''; // Limpia el campo de destino
//    document.getElementById('travel-date').value = ''; // Limpia el campo de fecha
//    document.getElementById('results-container').innerHTML = ''; // Limpia los resultados previos
//}

