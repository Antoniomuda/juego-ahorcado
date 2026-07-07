{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Partidas inacabadas')

{{-- Sección muestra vista de juego para que el usuario elija una letra --}}
@section('navbar')
 
    <li class="nav-item me">
        <a class="nav-link" href="juego.php">Volver</a>
    </li>

@endsection

@section('usermenu')
@parent
@endsection

@section('content')
    <div class="container page-container">

        <div class="page-header-label">En curso</div>
        <div class="page-header-title">Partidas inacabadas</div>


        @if (!empty($inacabadas))
                @foreach($inacabadas as $inacabada)
                <div class='partida-card'>
                    <div>
                        <div class='partida-palabra'>

                            {{$inacabada->getPalabraDescubierta() }}

                        </div>
                        <div class="partida-meta">
                            Iniciada el {{ $inacabada->getInicio()->format('Y-m-d H:i:s') }}
                            . {{ $inacabada->getNumErrores() }} errores
                        </div>
                    </div>
                    <a href="juego.php?botonjuegapartida&idpartida={{ $inacabada->getId() }}"
           class="btn-reanudar">
            Reanudar
                    </a>                  
                </div>
            @endforeach
        @else
            <div class="no-partidas">No hay partidas inacabadas todavía.</div>

        @endif


    </div>
@endsection
@push('scripts')
    <script src="assets/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="assets/js/resolver.js"></script>
@endpush


