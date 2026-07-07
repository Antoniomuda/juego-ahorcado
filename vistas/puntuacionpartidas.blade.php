@extends('app')
@section('title', 'Puntuación')
@section('navbar')
    <li class="nav-item">
        <a class="nav-link" href="juego.php">Volver</a>
    </li>
@endsection
@section('usermenu')
@parent
@endsection
@section('content')
    <div class="container page-container" >

        <div >
            <div class="page-header-label" >Sesión actual</div>
            <div class="page-header-title" >Puntuación de partidas</div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-label">Total puntos</div>
                <div class="stat-card-value">{{ $totalPuntos }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-label">Ganadas</div>
                <div class="stat-card-value" >{{ $ganadas }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-label">Perdidas</div>
                <div class="stat-card-value" >{{ $perdidas }}</div>
            </div>
        </div>

        <div class="game-card">
            @if(!empty($jugadas))
                <table class="partidas-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Palabra</th>
                            <th>Errores</th>
                            <th>Puntos</th>
                            <th>Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jugadas as $jugada)
                            <tr>
                                <td >{{ $loop->iteration }}</td>
                                <td class="palabra-cell">{{ $jugada->getPalabraSecreta() }}</td>
                                <td class="numero-cell"> {{ $jugada->getNumErrores() }}</td>
                                <td class="puntos-cell" >{{ $jugada->getPuntuacion() }}</td>
                                <td>
                                    @if($jugada->esPalabraDescubierta())
                                        <span class="badge-ganada">Ganada</span>
                                    @else
                                        <span class="badge-perdida">Perdida</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr class="table-footer-row">
                            <td class="total-puntos-cell" colspan="3">Total sesión</td>
                            <td >{{ $totalPuntos }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @else
            <div class="no-partidas">
            No hay partidas jugadas todavía
                </div>
            @endif
        </div>
    </div>
@endsection