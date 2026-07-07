<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap-icons/bootstrap-icons.css">
        <link rel="stylesheet" href="assets/css/stylesheet.css">

    </head>
    <body>
    <nav class="navbar navbar-expand-sm navbar-dark" >        
        <div class="container-fluid">
            <a class="navbar-brand">
                    Ahorcado.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    @yield('navbar')
                    @section('usermenu')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ (isset($usuario)) ? $usuario->getNombre() : "" }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="index.php?botonlogout">Logout</a></li>
                                <li><a class="dropdown-item" href="index.php?botonperfil">Perfil</a></li>
                                <li><a class="dropdown-item" href="index.php?botonbaja">Baja</a></li>


                            </ul>
                        </li>
                    @show
                </ul>
            </div>
        </div>
    </nav>
    @yield('mensaje')
    @yield('content')

    <script src="assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>


