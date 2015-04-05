<?php

namespace dlds\components\validators\isocode;

class Siren extends ISOValidator {

    const DEFAULT_LENGTH = 9;

    /**
     * @var int lenght
     */
    public $length = self::DEFAULT_LENGTH;

    /**
     * @inheritdoc
     */
    public function validateValue($value, $params = [])
    {
        if (!is_numeric($value))
        {
            return false;
        }

        if (strlen($value) != $this->length)
        {
            return false;
        }

        $len = strlen($value);
        $sum = 0;
        for ($i = 0; $i < $len; $i++)
        {
            $indice = ($len - $i);
            $tmp = (2 - ($indice % 2)) * $value[$i];
            if ($tmp >= 10)
            {
                $tmp -= 9;
            }
            $sum += $tmp;
        }

        return (($sum % 10) == 0);
    }

}
