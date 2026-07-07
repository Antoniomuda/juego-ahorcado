<?php

namespace App\Modelo;

use App\Almacen\IAlmacenPalabras;
use DateTime;
use App\Servicios\AnalizadorComplejidad;

/**
 * Clase que representa una partida del juego del ahorcado
 */
class Partida {

    /**
     * @var int id de la partida
     */
    private int $id;

    /**
     * @var int $numErrores Número de errores cometidos en la partida
     */
    private int $numErrores = 0;

    /**
     * @var $palabraSecreta Palabra secreta usada en la partida
     */
    private string $palabraSecreta;

    /**
     * @var $palabraDescubierta Estado de la palabra según va siendo descubierta. Por ejemplo c_c_e
     */
    private string $palabraDescubierta;

    /**
     * @var $letras Lista de jugadas que ha realizado el jugador en la partida
     */
    private string $letras = "";

    /**
     * @var $manNumErrores Número de errores permitido en la partida
     */
    private int $maxNumErrores;

    /**
     * @var $idUsuario id del usuario que juega la partida
     */
    private int $idUsuario;

    /**
     * @var $inicio de la partida
     */
    private string $inicio;

    /**
     * @var $fin de la partida
     */
    private ?string $fin = null;

    /**
     * @var $complejidad de la partida
     */
    private int $complejidad = 0;

    /**
     * Constructor de la clase Hangman
     * 
     * @param AlmacenPalabrasInterface $almacen Almacen de donde obtener palabras para el juego
     * @param int $maxNumErrores Número maximo de errores
     * 
     * @returns Hangman
     */
    public function __construct(
            IAlmacenPalabras $almacen = null,
            int $maxNumErrores = null,
            AnalizadorComplejidad $analizador = null,
            string $complejidadRequerida = null
    ) {

        if (func_num_args() > 0) {


            if ($analizador !== null) {
                if (str_contains($complejidadRequerida, '-')) {
                    $partes = explode('-', $complejidadRequerida);
                    $min = (int) $partes[0];
                    $max = (int) $partes[1];
                } else {
                    $min = (int) $complejidadRequerida;
                    $max = $min;
                }
                do {
                    $palabra = strtoupper($almacen->obtenerPalabraAleatoria());
                    $complejidad = $analizador->complejidadPalabra($palabra);
                } while ($complejidad < $min || $complejidad > $max);
                
                $this->setPalabraSecreta($palabra);
                $this->setPalabraDescubierta(preg_replace('/\w+?/', '_', $palabra));
                $this->setComplejidad($complejidad);
                $this->maxNumErrores = $maxNumErrores;
                $this->inicio = (new DateTime('now'))->format('Y-m-d H:i:s');
                
            } else {
                $this->setPalabraSecreta(strtoupper($almacen->obtenerPalabraAleatoria()));
                // Inicializa la estado de la palabra descubierta a una secuencia de guiones, uno por letra de la palabra oculta
                $this->setPalabraDescubierta(preg_replace('/\w+?/', '_', $this->getPalabraSecreta()));
                $this->maxNumErrores = $maxNumErrores;
                $this->inicio = (new DateTime('now'))->format('Y-m-d H:i:s');
            }
        }
    }

    /**
     * Recupera el identificador del usuario
     * 
     * @returns int Id del usuario
     */
    public function getIdUsuario(): int {
        return $this->idUsuario;
    }

    /**
     * Recupera el inicio de la partida
     * 
     * @returns DateTime del inicio de la partida
     */
    public function getInicio(): DateTime {
        return new DateTime($this->inicio);
    }

    /**
     * Recupera el fin de la partida
     * 
     * @returns DateTime fecha fin de la partida
     */
    public function getFin(): ?DateTime {
        return $this->fin ? new DateTime($this->fin) : null;
    }

    /**
     * Recupera la complejidad elegida por el usuario
     * 
     * @returns int complejidad de la partida
     */
    public function getComplejidad(): int {
        return $this->complejidad;
    }

    /**
     * Establece el identificador del usuario
     * 
     * @param int Id del usuario
     * 
     * @returns void
     */
    public function setIdUsuario(int $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    /**
     * Establece el inicio de la partida
     * 
     * @param DateTiem de inicio de la partida
     * 
     * @returns void
     */
    public function setInicio(DateTime $inicio): void {
        $this->inicio = $inicio->format('Y-m-d H:i:s');
    }

    /**
     * Establece  el fin de la partida
     * 
     * @param DateTime de la partida
     * 
     * @returns void
     */
    public function setFin(DateTime $fin): void {
        $this->fin = $fin->format('Y-m-d H:i:s');
    }

    /**
     * Establece  la complejidad de la partida
     * 
     * @param int complejidad de la partidas
     * 
     * @returns void
     */
    public function setComplejidad(int $complejidad): void {
        $this->complejidad = $complejidad;
    }

    /**
     * Recupera el identificador de la partida
     * 
     * @returns int Id de la partida
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Establece el identificador de la partida
     * 
     * @param int Id de la partida
     * 
     * @returns void
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * Recupera la palabra secreta de la partida
     * 
     * @returns string Palabra secreta de la partida
     */
    public function getPalabraSecreta(): string {
        return $this->palabraSecreta;
    }

    /**
     * Establece la palabra secreta de la partida
     * 
     * @param string $palabraSecreta Palabra secreta de la partida
     * 
     * @returns void
     */
    public function setPalabraSecreta(string $palabraSecreta): void {
        $this->palabraSecreta = $palabraSecreta;
    }

    /**
     * Recupera el estado de la palabra descubierta de la partida
     * 
     * @returns string Estado de la palabra descubierta de la partida
     */
    public function getPalabraDescubierta(): string {
        return $this->palabraDescubierta;
    }

    /**
     * Establece el estado de la palabra descubierta de la partida
     * 
     * @param string $palabraDescubierta El estado de la palabra descubierta de la partida
     * 
     * @returns void
     */
    public function setPalabraDescubierta(string $palabraDescubierta): void {
        $this->palabraDescubierta = $palabraDescubierta;
    }

    /**
     * Recupera el listado de letras jugadas en la partida
     * 
     * @returns string Listado de letras jugadas en la partida
     */
    public function getLetras(): string {
        return $this->letras;
    }

    /**
     * Establece el listado de letras jugadas en la partida
     * 
     * @param string $letras Listado de letras jugadas en la partida
     * 
     * @returns void
     */
    public function setLetras(string $letras): void {
        $this->letras = $letras;
    }

    /**
     * Recupera el número máximo de errores de la partida
     * 
     * @returns int Número máximo de errores de la partida
     */
    public function getMaxNumErrores(): int {
        return $this->maxNumErrores;
    }

    /**
     * Establece el número máximo de errores de la partida
     * 
     * @param int $maxNumErrores Número máximo de errores de la partida
     * 
     * @returns void
     */
    public function setMaxNumErrores($maxNumErrores): void {
        $this->maxNumErrores = $maxNumErrores;
    }

    /**
     * Recupera el número de errores cometido en la partida
     * 
     * @returns int Número de errores cometido en la partida
     */
    public function getNumErrores(): int {
        return $this->numErrores;
    }

    /**
     * Establece el número de errores cometido en la partida
     * 
     * @param int $numErrores Número de errores cometido en la partida
     * 
     * @returns void
     */
    public function setNumErrores($numErrores): void {
        $this->numErrores = $numErrores;
    }

    /**
     * Determina si una letra jugada es válida para el juego. Una letra es válida si se trata de una
     * letra en minúsculas o mayúsculas y si no ha sido jugada anteriormente
     * 
     * @param string $letra Letra elegida por el jugador
     * 
     * @returns bool Indica si la letra es válisa
     */
    public function esLetraValida(string $letra): bool {
        return ((strpos($this->getLetras(), strtoupper($letra)) === false) &&
                preg_match("/^[A-Za-z]$/", $letra));
    }

    /**
     * Comprueba la letra elegida por el jugador, modifica el estado de la palabra descubierta y añade la letra
     * 
     * @param string $letra Letra elegida por el jugador
     * 
     * @returns string El estado de la palabra descubierta
     */
    public function compruebaLetra(string $letra): string {
        $nuevaPalabraDescubierta = implode(array_map(function ($letraSecreta, $letraDescubierta) use ($letra) {
                    return ((strtoupper($letra) === $letraSecreta) ? $letraSecreta : $letraDescubierta);
                }, str_split($this->getPalabraSecreta()), str_split($this->getPalabraDescubierta())));
        if ($nuevaPalabraDescubierta == $this->getPalabraDescubierta()) {
            $this->numErrores++;
        } else {
            $this->setPalabraDescubierta($nuevaPalabraDescubierta);
        }
        $this->setLetras("{$this->getLetras()}$letra");
        return ($nuevaPalabraDescubierta);
    }

    /**
     * Comprueba si la palabra oculta el juego ya ha sido descubierta
     * 
     * @returns bool Verdadero si ya ha sido descubierta y falso en caso contrario
     */
    public function esPalabraDescubierta(): bool {
        // Si ya no hay guiones en la palabra descubierta
        return (!(strstr($this->getPalabraDescubierta(), "_")));
    }

    /**
     * Comprueba si la partida se ha acabado
     * 
     * @returns bool Verdadero si ya se ha acabado y falso en caso contrario
     */
    public function esFin(): bool {
        return ($this->esPalabraDescubierta() || ($this->getNumErrores() === $this->getMaxNumErrores()));
    }

    /**
     * Comprueba si la palabra que el usuario introduce para resolver la  palabra
     * es correcta
     * @returns bool Verdadero si ha acertado y falso en caso contrario
     */
    public function compruebaPalabra(string $palabra): bool {

        if ($palabra === $this->getPalabraSecreta()) {
            $this->setPalabraDescubierta($this->getPalabraSecreta());
            return true;
        } else {
            $this->setNumErrores($this->getMaxNumErrores());
            return false;
        }
    }

    public function getPuntuacion(): int {
        $puntuacion = 0;

        if ($this->getPalabraDescubierta() !== $this->getPalabraSecreta()) {
            $puntuacion = 0;
        } else {
            $puntuacion += 1;

            if (strlen($this->getPalabraDescubierta()) >= 3 && strlen($this->getPalabraDescubierta()) <= 5) {
                $puntuacion += 1;
            }
            if (preg_match('/[aeiouAEIOU]{2,}/', $this->getPalabraDescubierta())) {
                $puntuacion += 1;
            }
            if ($this->getNumErrores() < 3) {
                $puntuacion += 1;
            }
        }
        return $puntuacion;
    }

    /*
     * Calcula la pista que corresponde a la letra no descubierta que más 
     * ocurrencias tiene en la palabra, en caso de
     * empate se elige la primera letra en orden alfabético.
     */

    public function darPista() {
        $pista = "";

        for ($i = 0; $i < strlen($this->getPalabraSecreta()); $i++) {

            if ($this->getPalabraDescubierta()[$i] === "_") {
                $pista = $this->getPalabraSecreta()[$i];
            }
        }
        return $pista;
    }
    
  
}
