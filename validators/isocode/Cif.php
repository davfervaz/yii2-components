<?php

namespace dlds\components\validators\isocode;

class Cif extends ISOValidator {

    /**
     * CIF assets
     */
    const CODES = 'JABCDEFGHI';
    const PATTERN = '/^[ABCDEFGHJKNPQRSUVW]{1}/';
    const NUM = ['A', 'B', 'E', 'H'];
    const LETTER = ['K', 'P', 'Q', 'S'];
    const LENGTH = 9;

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        $codes = self::CODES;

        if (self::LENGTH !== strlen($value))
        {
            return false;
        }

        $value = strtoupper(trim($value));
        $sum = (string) Nif::getCifSum($value);

        $n = (10 - substr($sum, -1)) % 10;

        if (preg_match(self::PATTERN, $value))
        {
            if (in_array($value[0], self::NUM))
            {
                // Numerico
                return ($value[8] == $n);
            }
            elseif (in_array($value[0], self::LETTER))
            {
                // Letras
                return ($value[8] == $codes[$n]);
            }
            else
            {
                // Alfanumérico
                if (is_numeric($value[8]))
                {
                    return ($value[8] == $n);
                }
                else
                {
                    return ($value[8] == $codes[$n]);
                }
            }
        }

        return false;
    }

}
