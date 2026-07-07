<?php

namespace App\DAO;

use PDO;
use PDOException;
use App\Modelo\Partida;

/**
 * Description of PartidaDAO
 * 
 * 
 * Creo la clase PartidaDAO para dar persistencia a los objetos de la clase Partida e
  implementar los métodos crear, modificar, eliminar, ..... para realizar la inserción, actualización, eliminación,... de
  registros de la tabla partidas a partir de los objetos de la clase Partida utilizados en la
  aplicación. La implementación de los métodos se realiza con consultas preparadas
  de la librería PDO.
 *
 * @author murillodavilaantonio
 */
class PartidaDAO {

    private PDO $bd;

    public function __construct(PDO $bd) {

        $this->bd = $bd;
    }

    public function crear(Partida $partida): bool {

        $sql = "insert into partidas (numerrores, palabrasecreta, palabradescubierta,
                letras, maxnumerrores, inicio, fin, idusuario, complejidad)
                values (:numerrores, :palabrasecreta, :palabradescubierta,
                :letras, :maxnumerrores, :inicio, :fin, :idusuario, :complejidad);";

        try {

            $stmt = $this->bd->prepare($sql);
            $resultado = $stmt->execute([
                ':numerrores' => $partida->getNumErrores(),
                ':palabrasecreta' => $partida->getPalabraSecreta(),
                ':palabradescubierta' => $partida->getPalabraDescubierta(),
                ':letras' => $partida->getLetras(),
                ':maxnumerrores' => $partida->getMaxNumErrores(),
                ':inicio' => $partida->getInicio()->format('Y-m-d H:i:s'),
                ':fin' => $partida->getFin() ? $partida->getFin()->format('Y-m-d H:i:s') : null,
                ':idusuario' => $partida->getIdUsuario(),
                ':complejidad' => $partida->getComplejidad()
            ]);
            $partida->setId($this->bd->lastInsertId());
            return $resultado;
        } catch (PDOException $ex) {

            error_log($ex->getMessage());
            return false;
        }
    }

    public function modificar(Partida $partida): bool {

        $sql = "update partidas set numerrores=:numerrores, palabrasecreta=:palabrasecreta
            , palabradescubierta=:palabradescubierta, letras=:letras, maxnumerrores=:maxnumerrores
            , inicio=:inicio, fin=:fin, idusuario=:idusuario, complejidad=:complejidad where id=:id;";

        try {

            $stmt = $this->bd->prepare($sql);
            $resultado = $stmt->execute([
                ':numerrores' => $partida->getNumErrores(),
                ':palabrasecreta' => $partida->getPalabraSecreta(),
                ':palabradescubierta' => $partida->getPalabraDescubierta(),
                ':letras' => $partida->getLetras(),
                ':maxnumerrores' => $partida->getMaxNumErrores(),
                ':inicio' => $partida->getInicio()->format('Y-m-d H:i:s'),
                ':fin' => $partida->getFin() ? $partida->getFin()->format('Y-m-d H:i:s') : null,
                ':idusuario' => $partida->getIdUsuario(),
                ':complejidad' => $partida->getComplejidad(),
                ':id' => $partida->getId()
            ]);

            return $resultado;
        } catch (PDOException $ex) {

            error_log($ex->getMessage());
            return false;
        }
    }

    public function recuperaInacabadasPorIdUsuario(int $idusuario): array {

        $sql = "select id, numerrores AS numErrores, palabrasecreta AS palabraSecreta, 
             palabradescubierta AS palabraDescubierta, letras, maxnumerrores AS maxNumErrores,
             inicio, idusuario AS idUsuario, complejidad
             from partidas where idusuario=:idusuario and fin is NULL";

        try {
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([
                ':idusuario' => $idusuario
            ]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, Partida::class);
            $resultado = $stmt->fetchAll();
            return $resultado;
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return [];
        }
    }

    public function recuperaPorId(int $id): ?Partida {

        $sql = "select id, numerrores AS numErrores, palabrasecreta AS palabraSecreta, 
             palabradescubierta AS palabraDescubierta, letras, maxnumerrores AS maxNumErrores,
             inicio, idusuario AS idUsuario, complejidad
             from partidas where id=:id;";

        try {
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([
                ':id' => $id
            ]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, Partida::class);
            $resultado = $stmt->fetch();
            return $resultado ?: null;
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
        }
    }

    public function recuperaPorIdUsuario(int $idusuario): array {

        $sql = "select id, 
       numerrores AS numErrores, 
       palabrasecreta AS palabraSecreta, 
       palabradescubierta AS palabraDescubierta, 
       letras, 
       maxnumerrores AS maxNumErrores,
       inicio, fin,
       idusuario AS idUsuario,
       complejidad
from partidas where idusuario=:idusuario";

        try {
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([
                ':idusuario' => $idusuario
            ]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, Partida::class);
            $resultado = $stmt->fetchAll();
            return $resultado ?? [];
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
        }
    }
}
