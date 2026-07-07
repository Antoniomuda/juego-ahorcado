<?php

namespace App\DAO;

use PDO;
use PDOException;
use App\Modelo\Usuario;

class UsuarioDAO {

    /**
     * @var $bd Conexión a la Base de Datos
     */
    private PDO $bd;

    /**
     * Constructor de la clase UsuarioDAO
     * 
     * @param PDO $bd Conexión a la base de datos
     * 
     * @returns UsuarioDAO
     */
    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function crea(Usuario $usuario): bool {

        $sql = "insert into usuarios (nombre, clave, email, nivel) values(:nombre, :clave, :email, :nivel);";

        try {
            $stmt = $this->bd->prepare($sql);
            $resultado = $stmt->execute([
                ':nombre' => $usuario->getNombre(),
                ':clave' => $usuario->getClave(),
                ':email' => $usuario->getEmail(),
                ':nivel' => $usuario->getNivel()->value
            ]);

            $usuario->setId($this->bd->lastInsertId());
            return $resultado;
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return false;
        }
    }

    public function modifica(Usuario $usuario): bool {

        $sql = "update usuarios set nombre=:nombre, clave=:clave, email=:email, nivel=:nivel where id=:id;";

        try {
            $stmt = $this->bd->prepare($sql);

            $resultado = $stmt->execute([
                ':nombre' => $usuario->getNombre(),
                ':clave' => $usuario->getClave(),
                ':email' => $usuario->getEmail(),
                ':nivel' => $usuario->getNivel()->value,
                ':id' => $usuario->getId()
            ]);
            return $resultado;
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return false;
        }
    }

    public function elimina(int $id): bool {
        
        $sql= "delete from usuarios where id=:id;";
        
        try{
            $stmt = $this->bd->prepare($sql);
            $resultado = $stmt->execute([
                'id'=>$id
            ]);
            return $resultado ;
                    
            
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return false;
            

        }
        
    }

    /**
     * Recupera un objeto usuario dado su nombre de usuario y clave
     * 
     * @param string $nombre Nombre de usuario
     * @param string $pwd Clave del usuario
     * 
     * @returns Usuario que corresponde a ese nombre y clave o null en caso contrario
     */
    public function recuperaPorCredencial(string $nombre, string $pwd): ?Usuario {

        $sql = 'select * from usuarios where nombre=:nombre and clave=:pwd';

        try {

            $stmt = $this->bd->prepare($sql);
            $stmt->execute([
                ":nombre" => $nombre,
                ":pwd" => $pwd
            ]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
            $usuario = $stmt->fetch();

            return ($usuario ?: null);
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
        }
    }
}
