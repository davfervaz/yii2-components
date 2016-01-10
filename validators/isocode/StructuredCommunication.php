<?php

namespace dlds\components\validators\isocode;

class StructuredCommunication extends ISOValidator {

    const CODE_LENGTH = 12;

    public function validateValue($value)
    {
        if (self::CODE_LENGTH !== strlen($value))
        {
            return false;
        }

        $sequences = substr($value, 0, 10);
        $key = substr($value, -2);
        $control = $sequences % 97; // final control must be a 2-digits:
        $control = (1 < strlen($control)) ? $control : sprintf("0%d", $control);

        return $key === $control;
    }

}
