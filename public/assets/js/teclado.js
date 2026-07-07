// Enviar letra con el teclado visual
function enviarLetra(letra) {
    document.getElementById('letraInput').value = letra;
    document.getElementById('formJuego').submit();
}

// Mostrar/ocultar input de resolver al pulsar el botón
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('keypress', function (e) {
        if (document.activeElement.id === 'inputResolver')
            return;
        var letra = e.key.toUpperCase();
        if (/^[A-ZÑ]$/.test(letra) && !document.getElementById('formJuego').querySelector('[disabled]')) {
            enviarLetra(letra);
        }
    });

    var botonResolver = document.getElementById('botonresolverpartida');
    if (botonResolver) {
        botonResolver.addEventListener('click', function () {
            var input = document.getElementById('inputResolver');
            var confirmar = document.getElementById('botonconfirmarresolver');

            input.style.display = 'block';
            confirmar.style.display = 'block';
            setTimeout(function(){
                 input.focus();
            }, 100);

        });
    }

});