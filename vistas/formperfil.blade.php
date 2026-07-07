{{-- Usamos la vista app como plantilla --}}
@extends('app')
{{-- Sección aporta el título de la página --}}
@section('title', 'Modifica datos de tu perfil')
@section('usermenu')
    <a class="nav-link"  href="index.php?botonregistrate">Volver</a>
    @parent
@endsection
{{-- Sección que muestra los campos de info de cada usuario para poder moficarlos--}}
@section('content')
    <div class="login-container">
        <div class="login-card">

            <h2 class="login-title">Modifica tus datos</h2>
            <div>
                <form method="POST" action="index.php?botonperfil" id='formlogin'>
                    <div>                            
                        <label for="inputNombre" class="login-label">Nombre</label>
                        <div >
                            <input id="inputNombre" type="text"
                               class="login-input" placeholder="Nombre" name="nombre" value="{{ $_SESSION['usuario']->getNombre() ?? '' }}">
                        </div>
                    </div>
                    <div >
                        <label for="inputPassword" class="login-label">Password</label>
                        <div>
                            <input type="password"
                               class="login-input" id="inputPassword" placeholder="Password" name="clave" value="{{ $_SESSION['usuario']->getClave() ?? ''}}">
                        </div>        
                    </div>
                    <div>
                        <label for="inputEmail" class="login-label">Email</label>
                        <div>
                            <input type="email"
                               class="login-input" id="inputEmail" placeholder="Email" name="email" value="{{ $_SESSION['usuario']->getEmail() ?? ''}}">
                        </div>        
                    </div>
                    <div >
                        <label for="selectNivel" class="login-label">Nivel</label>
                        <div>
                            <select class="login-select" name="nivel" id="nivel">             
                                <option value="Principiante" {{ $_SESSION['usuario']->getNivel()->value === 'Principiante' ? 'selected' : '' }}>Principiante</option>                                
                                <option value="Principiante" {{ $_SESSION['usuario']->getNivel()->value === 'Intermedio' ? 'selected' : '' }}>Intermedio</option>                                
                                <option value="Principiante" {{ $_SESSION['usuario']->getNivel()->value === 'Avanzado' ? 'selected' : '' }}>Avanzado</option>                          
                            </select>
                        </div>        
                    </div>
                    <div>
                        <input type="submit" class="login-btn" name="botoncambiar" value="Confirmar cambios">
                    </div>

                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
