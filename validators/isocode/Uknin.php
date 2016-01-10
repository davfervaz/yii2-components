<?php

namespace dlds\components\validators\isocode;

class Uknin extends ISOValidator {

    const PATTERN = "/^(?!BG|GB|NK|KN|TN|NT|ZZ)[ABCEGHJ-PRSTW-Z][ABCEGHJ-NPRSTW-Z]\d{6}[A-D]$/";

    public function validateValue($value)
    {
        return (boolean) preg_match(self::PATTERN, $value);
    }

}
