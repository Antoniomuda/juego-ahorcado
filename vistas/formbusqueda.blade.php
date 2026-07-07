{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Búsqueda de Partidas')

{{-- Sección muestra vista de juego para que el usuario elija una letra --}}
@section('navbar')
    <li class="nav-item me-5">
        <a class="nav-link" aria-current="page" href="juego.php">Volver</a>
    </li>
@endsection

@section('usermenu')
@parent
@endsection

@section('content')
    <div class="login-container">
        <div class="login-card">
        <h2 class="login-title">Búsqueda de partidas jugadas</h2>
        <form id="formulariobusqueda" action="juego.php?petformbusqueda" method="POST">

            <label class="login-label" for="rango">Rango de letras de la palabra secreta:</label>
            <input class="login-input" type="text" name="rango" id="rango" placeholder="2-4" value="{{ $rango ?? '' }}">
    @if (!empty($errores['rango']))
            <span class="login-field-error">{{ $errores['rango'] }}</span>
            @endif

            <label class="login-label" for="letras">Letras palabra secreta:</label>
            <input class="login-input" type="text" name="letras" id="letras" placeholder="aedrtTU" value="{{ $letras ?? '' }}">
    @if (!empty($errores['letras']))
            <span class="login-field-error">{{ $errores['letras'] }}</span>
            @endif

            <input class="login-btn" type="submit" id="botonBusqueda" name="botonBusqueda" value="Buscar partidas">
        </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="assets/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="assets/js/resolver.js"></script>
@endpush


