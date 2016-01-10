<?php

namespace dlds\components\validators\isocode;

class Ean13 extends ISOValidator {

    const LENGTH = 13;
    const PATTERN = '/\\d{13}/i';

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validateValue($value)
    {
        // removing hyphens
        $value = str_replace(" ", "", $value);
        $value = str_replace("-", "", $value); // this is a dash
        $value = str_replace("‐", "", $value); // this is an authentic hyphen
        if (strlen($value) != self::LENGTH)
        {
            return false;
        }
        if (!preg_match(self::PATTERN, $value))
        {
            return false;
        }
        $check = 0;
        for ($i = 0; $i < 13; $i+=2)
        {
            $check += (int) substr($value, $i, 1);
            $check += 3 * substr($value, $i + 1, 1);
        }

        return $check % 10 == 0;
    }

}
