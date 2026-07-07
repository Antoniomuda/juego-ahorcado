// Inicio Sección JQuery

// Definición del manejador de eventos para el botón de resolver (JQuery) 
$(document).ready(function () {
    $("#botonconfirmarresolver").click(resolverPartida);
});
// Función para iniciar el proceso AJAX de resolución de partida y recoger los resultados y mostrarlos en la página (JQuery) 
function resolverPartida(e) {
    e.preventDefault();

    $.ajax({
        url: 'juego.php',
        type: 'POST',
        data: {
            botonresolverpartida: true,
            letra: $('#inputResolver').val()
        },
        success: function (respuesta) {
            console.log(respuesta);
            let datos = JSON.parse(respuesta);
            console.log(datos);
            console.log(document.getElementById('solucion'));
            console.log(document.getElementById('secreta'));

            if (datos.resultado) {
                muestraTexto('solucion', 'Enhorabuena!');
            } else {
                muestraTexto('solucion', 'Has perdido!');
            }
            muestraTexto('secreta', datos.secreta);
            $('#palabra').text(datos.secreta.split('').join(' '));

            deshabilitaBoton('botonresolverpartida');
            deshabilitaBoton('botonpista');
            deshabilitaBoton('botonconfirmarresolver');
        }
    });

}
;
// Función que muestra un texto en el elemento cuyo identificador es id (Jquery)
function muestraTexto(id, texto) {
    $(`#${id}`).text(texto);
}


// Función que deshabilita un botón dado su id (Jquery)
function deshabilitaBoton(idBoton) {
    $(`#${idBoton}`).prop('disabled', true);
}

