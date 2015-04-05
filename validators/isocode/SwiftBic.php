<?php

namespace dlds\components\validators\isocode;

class SwiftBic extends ISOValidator {

    const PATTERN = "/^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$/";

    public function validateValue($value)
    {
        return (boolean) preg_match(self::PATTERN, $value);
    }

}
