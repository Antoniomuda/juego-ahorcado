{{-- Usamos la vista app como plantilla --}}
@extends('app')
{{-- Sección aporta el título de la página --}}
@section('title', 'Formulario registro nuev@s usuari@s')
@section('usermenu')

    <a class='login-link' href="index.php?botonregistrate">Regístrate</a>
    @parent
@endsection
{{-- Sección muestra el formulario de registro de nuev@s usuari@s --}}
@section('content')
    <div class="login-container">
        <div class='login-card'>

            <h2 class="login-title">Regístrate</h2>
            <div>
                <form method="POST" action="index.php?botonregistrate" id='formlogin'>
                    <div>                            
                        <label for="inputNombre" class="login-label">Nombre</label>
                        <div>
                            <input id="inputNombre" type="text"
                               class="login-input" placeholder="Nombre" name="nombre">
                            @if(!empty($errores['nombre']))
                                <span class="login-field-error">{{ $errores['nombre'] }}</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label for="inputPassword" class="login-label">Password</label>
                        <div>
                            <input type="password"
                               class="login-input" id="inputPassword" placeholder="Password" name="clave">
                            @if(!empty($errores['clave']))
                                <span class="login-field-error">{{ $errores['clave'] }}</span>
                            @endif
                        </div>        
                    </div>
                    <div >
                        <label for="inputEmail" class="login-label">Email</label>
                        <div >
                            <input type="email"
                               class="login-input" id="inputEmail" placeholder="Email" name="email">

                            @if(!empty($errores['email']))
                                <span class="login-field-error">{{ $errores['email'] }}</span>
                            @endif
                        </div>        
                    </div>
                    <div class="mb-3">
                        <div>
                            <input type="submit" class="login-btn" name="botonregistra" value="Regístrate">
                        </div>
                    </div>
                </form>
                <a href="index.php" class="login-link">
                    ¿Ya tienes cuenta? <span>Inicia sesión</span>
                </a>
            </div>
        </div>
    </div>
    </div>
@endsection
