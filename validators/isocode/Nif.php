<?php

namespace dlds\components\validators\isocode;

class Nif extends ISOValidator {

    const CODES = 'TRWAGMYFPDXBNJZSQVHLCKE';

    public function validateValue($value, $params = [])
    {
        $nifCodes = self::CODES;

        if (9 !== strlen($value))
        {
            return false;
        }
        
        $value = strtoupper(trim($value));

        $sum = (string) self::getCifSum($value);
        $n = 10 - substr($sum, -1);

        if (preg_match('/^[0-9]{8}[A-Z]{1}$/', $value))
        {
            // DNIs
            $num = substr($value, 0, 8);

            return ($value[8] == $nifCodes[$num % 23]);
        }
        elseif (preg_match('/^[XYZ][0-9]{7}[A-Z]{1}$/', $value))
        {
            // NIEs normales
            $tmp = substr($value, 1, 7);
            $tmp = strtr(substr($value, 0, 1), 'XYZ', '012') . $tmp;

            return ($value[8] == $nifCodes[$tmp % 23]);
        }
        elseif (preg_match('/^[KLM]{1}/', $value))
        {
            // NIFs especiales
            return ($value[8] == chr($n + 64));
        }
        elseif (preg_match('/^[T]{1}[A-Z0-9]{8}$/', $value))
        {
            // NIE extraño
            return true;
        }

        return false;
    }

    /**
     * Used to calculate the sum of the CIF, DNI and NIE
     *
     * @param string $cif
     *
     * @return mixed
     */
    public static function getCifSum($cif)
    {
        $sum = $cif[2] + $cif[4] + $cif[6];

        for ($i = 1; $i < 8; $i += 2)
        {
            $tmp = (string) (2 * $cif[$i]);
            $tmp = $tmp[0] + ((strlen($tmp) == 2) ? $tmp[1] : 0);
            $sum += $tmp;
        }

        return $sum;
    }

}
