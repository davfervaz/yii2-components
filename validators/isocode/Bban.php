<?php

namespace dlds\components\validators\isocode;

class Bban extends ISOValidator {

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        if (mb_strlen($value) !== 23)
        {
            return false;
        }

        $key = substr($value, -2);
        $bank = substr($value, 0, 5);
        $branch = substr($value, 5, 5);

        $account = substr($value, 10, 11);
        $account = strtr($account, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', '12345678912345678923456789');

        return (97 - bcmod(89 * $bank + 15 * $branch + 3 * $account, 97) === (int) $key);
    }

}
