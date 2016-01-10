<?php

namespace dlds\components\validators\isocode;

class Isbn10 extends ISOValidator {

    const PATTERN = '/\\d{9}[0-9xX]/i';

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        // removing hyphens
        $value = str_replace(" ", "", $value);
        $value = str_replace("-", "", $value); // this is a dash
        $value = str_replace("‐", "", $value); // this is an authentic hyphen
        if (strlen($value) != 10)
        {
            return false;
        }

        if (!preg_match(self::PATTERN, $value))
        {
            return false;
        }

        $check = 0;
        for ($i = 0; $i < 10; $i++)
        {
            if ($value[$i] == "X")
            {
                $check += 10 * intval(10 - $i);
            }
            $check += intval($value[$i]) * intval(10 - $i);
        }

        return $check % 11 == 0;
    }

}
