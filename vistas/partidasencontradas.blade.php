{{-- Usamos la vista app como plantilla --}}
@extends('app')

{{-- Sección aporta el título de la página --}}
@section('title', 'Partidas encontradas')

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
    <div class="container page-container">
        <div class="page-header-label">Resultados</div>
        <div class="page-header-title">Partidas encontradas</div>



        @if (!empty($partidasEncontradas))
                @foreach($partidasEncontradas as $partida)
                <div class="partida-encontrada-card">
                    <div class="partida-encontrada-header">
                        <div class="partida-palabra">{{ $partida->getPalabraSecreta() }}</div>
        @if($partida->esPalabraDescubierta())
                            <span class="badge-ganada">Ganada</span>
                        @else
                            <span class="badge-perdida">Perdida</span>
                        @endif
                    </div>
                    <div class="partida-encontrada-meta">
                        <div>Errores <span>{{ $partida->getNumErrores() }}</span></div>
                        <div>Inicio <span>{{ $partida->getInicio()->format('d/m/Y H:i') }}</span></div>
                        <div>Fin <span>{{ $partida->getFin() ? $partida->getFin()->format('d/m/Y H:i') : '—' }}</span></div>
                        <div>Letras <span>{{ $partida->getLetras() ?: '—' }}</span></div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-partidas"> No se han encontrado partidas con esos criterios de búsqueda.</div>

        @endif

    </div>

@endsection
@push('scripts')
    <script src="assets/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="assets/js/resolver.js"></script>
@endpush


