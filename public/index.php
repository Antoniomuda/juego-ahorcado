<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/**
 *  --- Lógica del script --- 
 * 
 * Establece conexión a la base de datos PDO
 * Si el usuario ya está validado
 *   Si se solicita cerrar la sesión
 *     Destruyo la sesión
 *     Invoco la vista del formulario de login
 *   Si no redirección a juego para jugar una partida
 *  Si no 
 *   Si se pide procesar los datos del formulario
 *       Lee los valores del formulario
 *       Si los credenciales son correctos
 *       Redirijo al cliente al script de juego con una nueva partida
 *        Si no Invoco la vista del formulario de login con el flag de error
 *   Si no (En cualquier otro caso)
 *      Invoco la vista del formulario de login
 */
require "../vendor/autoload.php";
require "../src/error_handler.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\BD\BD;
use App\Modelo\Usuario;
use App\DAO\UsuarioDAO;
use App\Modelo\Nivel;

session_start();

// Inicializa el acceso a las variables de entorno

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Inicializa el acceso a las variables de entorno

$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

// Establece conexión a la base de datos PDO
try {
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $database = $_ENV['DB_DATABASE'];
    $usuario = $_ENV['DB_USUARIO'];
    $password = $_ENV['DB_PASSWORD'];
    $bd = BD::getConexion($host, $port, $database, $usuario, $password);
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}

$usuarioDAO = new UsuarioDAO($bd);
// Si el usuario ya está validado

if (isset($_SESSION['usuario'])) {
    // Si se solicita cerrar la sesión


    if (filter_has_var(INPUT_GET, 'botonlogout')) {
        // Destruyo la sesión
        session_unset();
        session_destroy();
        setcookie(session_name(), '', 0, '/');
        // Invoco la vista del formulario de login
        echo $blade->run("formlogin");
        
        
    } elseif (filter_has_var(INPUT_GET, 'botonperfil')) {


        if (filter_has_var(INPUT_POST, 'botoncambiar')) {

            $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
            $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_SPECIAL_CHARS));
            $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
            $nivel = filter_input(INPUT_POST, 'nivel');

            $errores = [];
            if ($nombre === "") {
                $errores['nombre'] = "El nombre de usuario es obligatorio.";
            } else if (!preg_match('/^\w{3,15}$/i', $nombre)) {
                $errores['nombre'] = "El nombre debe de estar formado por entre 3 y 15 careacteres de palabra.";
            }

            if ($clave === "") {
                $errores['clave'] = "La clave es obligatoria.";
            } else if (!ctype_digit($clave) || strlen($clave) !== 6) {
                $errores['clave'] = "La clave debe estar formada como mínimo de 6 números.";
            }
            if ($email !== "" && !filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = "El formato de email es inválido.";
            }

            if (empty($errores)) {


                $usuario = $_SESSION['usuario'];
                $usuario->setNombre($nombre);
                $usuario->setClave($clave);
                $usuario->setEmail($email);
                $usuario->setNivel(Nivel::from($nivel));
                $usuarioDAO->modifica($usuario);
                $_SESSION['usuario'] = $usuario;

                echo $blade->run('formlogin', compact ('usuario'));
                die;
            } else {

                echo $blade->run('formperfil', compact('nombre', 'clave', 'email', 'errores', 'usuario'));
                die;
            }
        } else {
            $usuario = $_SESSION['usuario'];
            echo $blade->run('formperfil', compact('usuario'));
            die;
        }
        
    }elseif(filter_has_var(INPUT_GET, 'botonbaja')){
        $usuario =$_SESSION['usuario'];
        $idUsuario = $usuario->getId();
        
        $resultado = $usuarioDAO->elimina($idUsuario);
        $mensaje ="";
        
        if ($resultado){
            $mensaje = "Baja realizada con éxito.";
        }
        
        echo $blade->run('formlogin', compact('mensaje'));
        die;
        
        
    } else {
        // Redirijo al cliente al script de gestión del juego
        header("Location:juego.php?botonnuevapartida");
        die;
    }

    // Si no 
} else {


    if (filter_has_var(INPUT_POST, 'botonproclogin')) {
        // Lee los valores del formulario
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW));
        $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_UNSAFE_RAW));
        $usuario = $usuarioDAO->recuperaPorCredencial($nombre, $clave);
        // Si los credenciales son correctos
        if ($usuario) {
            $_SESSION['usuario'] = $usuario;
            // Redirijo al cliente al script de juego con una nueva partida
            header("Location:juego.php?botonnuevapartida");
            die;
        }
        // Si los credenciales son incorrectos
        else {
            // Invoco la vista del formulario de login con el flag de error activado
            echo $blade->run("formlogin", ['error' => true]);
        }
        // En cualquier otro caso
    } elseif (filter_has_var(INPUT_GET, 'botonregistrate')) {


        if (filter_has_var(INPUT_POST, 'botonregistra')) {
            $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
            $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_SPECIAL_CHARS));
            $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));

            $errores = [];

            if ($nombre === "") {
                $errores['nombre'] = "El nombre de usuario es obligatorio.";
            } else if (!preg_match('/^\w{3,15}$/i', $nombre)) {
                $errores['nombre'] = "El nombre debe de estar formado por entre 3 y 15 careacteres de palabra.";
            }

            if ($clave === "") {
                $errores['clave'] = "La clave es obligatoria.";
            } else if (!ctype_digit($clave) || strlen($clave) !== 6) {
                $errores['clave'] = "La clave debe estar formada como mínimo de 6 números.";
            }
            if ($email !== "" && !filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = "El formato de email es inválido.";
            }


            if (empty($errores)) {


                $usuario = new Usuario($nombre, $clave, $email);
                $usuarioDAO->crea($usuario);

                echo $blade->run('formlogin');
                die;
            } else {

                echo $blade->run('formularioRegistro', compact('nombre', 'clave', 'email', 'errores'));
                die;
            }
        } else {
            echo $blade->run('formularioRegistro');
            die;
        }
    } else {
        // Invoco la vista del formulario de login
        echo $blade->run("formlogin");
    }
}