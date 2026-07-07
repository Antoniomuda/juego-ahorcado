<?php

namespace App\Modelo;
use App\Modelo\Nivel;

/**
 * Clase que representa al usuario que está usando la aplicación
 */
class Usuario {

    /**
     * @var string $id identificador del usuario
     */
    private string $id;

    /**
     * @var string $nombre nombre del usuario
     */
    private string $nombre;

    /**
     * @var string $clave Clave del usuario
     */
    private string $clave;

    /**
     * @var string $email Email del usuario
     */
    private ?string $email;

    /**
     * @var string $nivel nivel del usuario
     */
    private $nivel= Nivel::Principiante;

    /**
     * Constructor de la clase Usuario
     * 
     * @param string $nombre Nombre del usuario
     * @param string $clave Clave del usuario
     * @param string $email Email del usuario
     * 
     * @returns Hangman
     */
    public function __construct(?string $nombre = null, ?string $clave = null, ?string $email = null) {
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        if (!is_null($clave)) {
            $this->clave = $clave;
        }
        if (!is_null($email)) {
            $this->email = $email;
        }
        if (isset($this->nivel) && is_string($this->nivel)){
            $this->nivel = Nivel::from($this->nivel);
        }
    }

    /**
     * Recupera el Id del usuario
     * 
     * @returns int Id del usuario
     */
    public function getId(): ?int {
        return $this->id;
    }
    public function setId($id): void{
        $this->id=$id;
    }

    
       /**
     * Recupera el nivel del usuario
     * 
     * @returns string Nivel del usuario
     */
    public function getNivel(): Nivel {
        if (is_string($this->nivel)){
            $this->nivel = Nivel::from($this->nivel);
        }
        return $this->nivel;
    }

    
    /**
     * Establece el nivel del usuario
     * 
     * @param string $nivel  del usuario
     * 
     * @returns void
     */
    public function setNivel(Nivel $nivel): void {

        $this->nivel = $nivel;
    }

    /**
     * Recupera el nombre del usuario
     * 
     * @returns string Nombre del usuario
     */
    public function getNombre(): string {
        return $this->nombre;
    }

    /**
     * Establece el nombre del usuario
     * 
     * @param string $nombre Nombre del usuario
     * 
     * @returns void
     */
    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }

    /**
     * Recupera la clave del usuario
     * 
     * @returns string Clave del usuario
     */
    public function getClave(): string {
        return $this->clave;
    }

    /**
     * Establece la clave del usuario
     * 
     * @param string $clave Clave del usuario
     * 
     * @returns void
     */
    public function setClave(string $clave) {
        $this->clave = $clave;
    }

    /**
     * Recupera el email del usuario
     * 
     * @returns string Email del usuario
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * Establece el email del usuario
     * 
     * @param string $email Email del usuario
     * 
     * @returns void
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }
}
