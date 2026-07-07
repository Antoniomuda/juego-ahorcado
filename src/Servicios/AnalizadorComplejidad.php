<?php

namespace App\Servicios;

/**
 * Description of AnalizadorComplejidad
 *
 * @author murillodavilaantonio
 */
class AnalizadorComplejidad {

    public function complejidadPalabra(string $palabra): int {

        if (strlen($palabra) >= 5 && strlen($palabra) <= 8) {
            if (!preg_match('/[mntslcrbdp]/i', $palabra) && preg_match('/(ar|er|ir)/i', $palabra)) {
                return 0;
            }
        
        }
        if (strlen($palabra) >= 1 && strlen($palabra) <= 8) {
            if (!preg_match('/[aeiouAEIOU]{2,}/', $palabra)) {
                return 1;
            }
        
            return 2;
        }
        if (strlen($palabra) > 8) {
            if (!preg_match('/[zxqkhyw]/i', $palabra)) {
                return 3;
            }
        
            return 4;
        }
    }
}
