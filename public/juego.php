<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/**
 *  --- Lógica del script --- 
 * 
 * Establece conexión a la base de datos PDO
 * Si el usuario ya está validado
 *   Si se pide jugar con una letra
 *     Leo la letra
 *     Si no hay error en la letra introducida
 *       Solicito a la partida que compruebe la letra
 *     Invoco la vista de juego con los datos obtenidos
 *   Si no si se solicita una nueva partida
 *     Se crea una nueva partida
 *     Invoco la vista del juego para empezar a jugar
 *   Si no Invoco la vista de juego
 *  Si no (En cualquier otro caso)
 *      Invoco la vista del formulario de login
 */
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\Modelo\Partida;
use App\Almacen\AlmacenPalabrasFichero;
use App\DAO\PartidaDAO;
use App\BD\BD;
use App\Modelo\Usuario;
use App\Servicios\AnalizadorComplejidad;

session_start();

define("MAX_NUM_ERRORES", 5);

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

$bd = BD::getConexion(
        $_ENV['DB_HOST'],
        $_ENV['DB_PORT'],
        $_ENV['DB_DATABASE'],
        $_ENV['DB_USUARIO'],
        $_ENV['DB_PASSWORD']
);

$partidaDAO = new PartidaDAO($bd);

function obtenerPartidasPorCriteriosBusqueda(array $partidas,
        int $minNumLetras,
        int $maxNumLetras,
        string $lestrasPalabraSecreta): array {

    $resultado = [];

    foreach ($partidas as $partida) {
        if (strlen($partida->getPalabraSecreta()) >= $minNumLetras && strlen($partida->getPalabraSecreta()) <= $maxNumLetras) {

            $cumple = true;

            for ($i = 0; $i < strlen($lestrasPalabraSecreta); $i++) {
                if (strpos($partida->getPalabraSecreta(), strtoupper($lestrasPalabraSecreta[$i])) === false) {
                    $cumple = false;
                    break;
                }
            }
            if ($cumple) {
                $resultado [] = $partida;
            }
        }
    }
    return $resultado;
}

// Si el usuario ya está validado
if (isset($_SESSION['usuario'])) {

    $usuario = $_SESSION['usuario'];
    if (!isset($_SESSION['partidasAcabadas'])) {
        $_SESSION['partidasAcabadas'] = [];
    }

// Si se pide jugar con una letra

    if (filter_has_var(INPUT_POST, 'botonenviarjugada')) {
// Leo la letra

        $letra = trim(filter_input(INPUT_POST, 'letra', FILTER_UNSAFE_RAW));
        $partida = $_SESSION['partida'];
// Compruebo si la letra no es válida (carácter no válido o ya introducida)
        $error = !$partida->esLetraValida($letra);
        // Si no hay error compruebo la letra


        if (!$error) {

            $partida->compruebaLetra(strtoupper($letra));

            $_SESSION['partida'] = $partida;

            if ($partida->esFin()) {

                $partida->setFin(new DateTime('now'));
                $partidaDAO->modificar($partida);
                $_SESSION['partidasAcabadas'][] = $partida;
            }
        }
        // Sigo jugando
        echo $blade->run("juego", compact('usuario', 'partida', 'error'));

// Si no si se solicita una nueva partida
    } elseif (filter_has_var(INPUT_GET, 'botonnuevapartida')) { // Se arranca una nueva partida
        if (isset($_SESSION['partida'])) {

            $partida = $_SESSION['partida'];

            $partidaDAO->modificar($partida);
        }

        $analizador = new AnalizadorComplejidad();

        $rangosComplejidad = [
            'Principiante' => '0-1',
            'Intermedio' => '2-3',
            'Avanzado' => '4'
        ];

        $complejidadRequerida = $rangosComplejidad[$usuario->getNivel()->value];

        $rutaFichero = $_ENV['RUTA_ALMACEN_PALABRAS'];
        $almacenPalabras = new AlmacenPalabrasFichero($rutaFichero);

        $partida = new Partida($almacenPalabras, MAX_NUM_ERRORES, $analizador, $complejidadRequerida);
        $idUsuario = $usuario->getId();
        $partida->setIdUsuario($idUsuario);
        $partidaDAO->crear($partida);

        $_SESSION['partida'] = $partida;
        echo $blade->run("juego", compact('usuario', 'partida'));
    } else if (filter_has_var(INPUT_GET, 'botonpuntuacionpartidas')) {
        $idUsuario = $usuario->getId();

        $jugadas = $_SESSION['partidasAcabadas'];

        $jugadas = array_filter($_SESSION['partidasAcabadas'], fn($p) => $p !== null);
        $totalPuntos = array_sum(array_map(fn($p) => $p->getPuntuacion(), $jugadas));
        $ganadas = count(array_filter($jugadas, fn($p) => $p->esPalabraDescubierta()));
        $perdidas = count($jugadas) - $ganadas;
        echo $blade->run('puntuacionpartidas', compact('jugadas', 'idUsuario', 'usuario','totalPuntos', 'ganadas', 'perdidas'));
        die;

// Invoco la vista del juego para empezar a jugar
    } elseif (isset($_POST['botonresolverpartida'])) {


        $partida = $_SESSION['partida'];

        $palabra = trim(filter_input(INPUT_POST, 'letra', FILTER_SANITIZE_SPECIAL_CHARS));

        $resultado = $partida->compruebaPalabra(strtoupper($palabra));
        $partida->setFin(new DateTime('now'));
        $partidaDAO->modificar($partida);

        $_SESSION['partida'] = $partida;
        $_SESSION['partidasAcabadas'][] = $partida;

        echo json_encode([
            'resultado' => $resultado,
            'secreta' => $partida->getPalabraSecreta()
        ]);
        die;
    } else if (filter_has_var(INPUT_GET, 'botonpartidasinacabadas')) {

        $idUsuario = $usuario->getId();

        $inacabadas = $partidaDAO->recuperaInacabadasPorIdUsuario($idUsuario);

        echo $blade->run('partidasInacabadas', compact('inacabadas','usuario', 'idUsuario', 'usuario'));
        die;
    } else if (filter_has_var(INPUT_GET, 'botonjuegapartida')) {

        $idPartida = (int) filter_input(INPUT_GET, 'idpartida', FILTER_SANITIZE_NUMBER_INT);

        $partida = $partidaDAO->recuperaPorId($idPartida);
        $_SESSION['partida'] = $partida;

        echo $blade->run('juego', compact('usuario', 'partida'));
        die;
    } else if (filter_has_var(INPUT_POST, 'botonpista')) {

        $partida = $_SESSION['partida'];

        $pista = $partida->darPista();

        $_SESSION['partida'] = $partida;

        echo json_encode($pista);
        die;
    } else if (filter_has_var(INPUT_GET, 'petformbusqueda')) {

        if (isset($_POST['botonBusqueda'])) {

            $rango = trim(filter_input(INPUT_POST, 'rango', FILTER_SANITIZE_SPECIAL_CHARS));
            $letras = trim(filter_input(INPUT_POST, 'letras', FILTER_SANITIZE_SPECIAL_CHARS));
           
            $errores = [];

            if ($rango === '' || !preg_match('/^\d{1,2}-\d{1,2}$/', $rango)) {
                $errores['rango'] = "El rango no puede estar vacío, es obligatorio.";
            } else {
                $desgloseRango = explode('-', $rango);
                $min = (int) $desgloseRango[0];
                $max = (int) $desgloseRango[1];
                if ($min > 30 || $max > 30 || $min >= $max) {
                    $errores['rango'] = "El mínimo debe ser menor que el máximo y ambos menores de 30";
                }
            }


            if (strlen($letras) < 1 || strlen($letras) > 25 || $letras === '') {
                $errores['letras'] = "El número de letras debe estar entre 1 y 25 y no puede estar vacio.";
            }

            if (empty($errores)) {

                $partidas = $partidaDAO->recuperaPorIdUsuario($usuario->getId());

                $partidasEncontradas = obtenerPartidasPorCriteriosBusqueda($partidas, $min, $max, $letras);

                echo $blade->run('partidasencontradas', compact('partidasEncontradas','usuario'));
                die;
            } else {
                echo $blade->run('formbusqueda', compact('rango','usuario', 'letras', 'errores'));
                die;
            }
        }
        echo $blade->run('formbusqueda', compact('usuario'));
        die;
    } else { //En cualquier otro caso
        $partida = $_SESSION['partida'];
        echo $blade->run("juego", compact('usuario', 'partida'));
    }


// En otro caso se muestra el formulario de login
} else {
    echo $blade->run("formlogin");
}
