@extends('app')
@section('title', 'Juego')
@section('navbar')
    <li class="nav-item me-3">
        <a class="nav-link" href="juego.php?botonnuevapartida">Nueva Partida</a>
    </li>
    <li class="nav-item me-3">
        <a class="nav-link" href="juego.php?botonpuntuacionpartidas">Puntuación</a>
    </li>
    <li class="nav-item me-3">
        <a class="nav-link" href="juego.php?botonpartidasinacabadas">Mis Partidas inacabadas</a>
    </li>
    <li class="nav-item me-3">
        <a class="nav-link" href="juego.php?petformbusqueda">Buscador</a>
    </li>
@endsection
@section('usermenu')
@parent
@endsection
@section('content')
    @php
    $letrasUsadas = str_split($partida->getLetras());
    $letrasCorrectas = [];
    foreach ($letrasUsadas as $l) {
        if (strpos($partida->getPalabraSecreta(), $l) !== false) {
            $letrasCorrectas[] = $l;
        }
    }
    $letrasIncorrectas = array_diff($letrasUsadas, $letrasCorrectas);
    $filas = [
        ['A','B','C','D','E','F','G','H','I'],
        ['J','K','L','M','N','Ñ','O','P','Q'],
        ['R','S','T','U','V','W','X','Y','Z']
    ];
@endphp

    {{-- Formulario oculto que envía la letra --}}
    <form action="juego.php" method="POST" id="formJuego">
        <input type="hidden" name="letra" id="letraInput">
        <input type="hidden" name="botonenviarjugada" value="1">
    </form>

    <div class="container" style="max-width: 700px; padding: 24px 16px;">

        {{-- Mensaje resultado --}}
    @if($partida->esFin())
            <div class="mensaje-resultado" id="mensaje">
                {{ $partida->esPalabraDescubierta() ? "¡Enhorabuena!" : "¡Has perdido!" }}
            </div>
        @else
            <div class="mensaje-resultado" id="mensaje" style="min-height:48px;"></div>
        @endif

        <div class="game-card">
            {{-- Solucion AJAX resolver --}}
            <div id="solucion" class="mensaje-resultado"></div>
            <div id="secreta" class="mensaje-resultado"></div>
            {{-- Imagen ahorcado --}}
            @php $imgsHangman = ['Hangman-0.png','Hangman-1.png','Hangman-2.png','Hangman-3.png','Hangman-4.png','Hangman-5.png']; @endphp
            <div class="hangman-container">
                <img src="./assets/img/{{ $imgsHangman[$partida->getNumErrores()] }}"
                 style="height: 140px;" alt="Ahorcado">
            </div>

            {{-- Palabra --}}
            <div class="palabra-display" id="palabra">
                {{ implode(" ", str_split($partida->esFin() ? $partida->getPalabraSecreta() : $partida->getPalabraDescubierta())) }}
            </div>

            {{-- Pista --}}
            <div class="pista-texto" id="pista"></div>

            {{-- Teclado visual --}}
            <div class="teclado">
                @foreach($filas as $fila)
                    <div class="teclado-fila">
                        @foreach($fila as $tecla)
                            @php
                            $clase = '';
                            if (in_array($tecla, $letrasCorrectas)) $clase = 'correcta';
                            elseif (in_array($tecla, $letrasIncorrectas)) $clase = 'incorrecta';
                        @endphp
                            <button type="button"
                        class="tecla {{ $clase }}"
                                {{ ($partida->esFin() || $clase !== '') ? 'disabled' : '' }}
                        onclick="enviarLetra('{{ $tecla }}')">
                                {{ $tecla }}
                            </button>
                        @endforeach
                    </div>
                @endforeach
                <input type="text" 
                   id="inputResolver" 
                   class="resolver-input" 
                   placeholder="Escribe la palabra completa..."
                {{ $partida->esFin() ? 'disabled' : '' }}>

                <button type="button" id="botonconfirmarresolver" class="btn-accion" style="display:none; margin-top:8px;">
                Confirmar
                </button>
            </div>

            {{-- Footer del juego --}}
            <div class="game-footer">
                <div class="intentos-label">
                    Intentos · {{ $partida->getNumErrores() }} / {{ $partida->getMaxNumErrores() }}
                </div>
                <div style="display:flex; gap:20px;">
                    <button class="btn-accion" id="botonpista"
                        {{ $partida->esFin() ? 'disabled' : '' }}>
                    Pista
                    </button>
                    <button class="btn-accion" id="botonresolverpartida"
                        {{ $partida->esFin() ? 'disabled' : '' }}>
                    Resolver
                    </button>
                </div>
            </div>
        </div>

        {{-- Botón nueva partida --}}
        <a href="juego.php?botonnuevapartida" class="btn-nueva-partida">
        Comenzar Nueva Partida
        </a>



    </div>
@endsection

@push('scripts')
    <script src="assets/js/teclado.js"></script>
    <script src="assets/js/pista.js"></script>
    <script src="assets/js/resolver.js"></script>
@endpush