{{-- Usamos la vista app como plantilla --}}
@extends('app')
{{-- Sección aporta el título de la página --}}
@section('title', 'Formulario login')
@section('usermenu')
    <a class='login-link' href="index.php?botonregistrate">Registrate</a>
@endsection
{{-- Sección muestra el formulario de login del usuario --}}
@section('content')
    <div class="login-container">
        <div class="login-card">
            @if (isset($error)) 
                <div class="alert alert-danger" role="alert">Error Credenciales</div>
            @endif
            <h2 class="login-title">Login</h2>
            <div>

                <form method="POST" action="index.php" id='formlogin'>
                    <label class='login-label' for='inputNombre'>Nombre</label>
                    <input class='login-input' id='inputNombre' type='text' placeholder="Tu nombre de usuaurio"
                       name='nombre'>

                    <label class='login-label' for='inputPassword'>Password</label>
                <input class='login-input' id='inputPassword' type='password' placeholder="Tu contraseña"
                       name='clave'>
                <input class='login-btn' type='submit' name='botonproclogin' value='Entrar'>
            </form>
            <a href="index.php?botonregistrate" class="login-link">¿No tienes cuenta? <span>Regístrate</span></a>
        </div>
    </div>
@endsection
