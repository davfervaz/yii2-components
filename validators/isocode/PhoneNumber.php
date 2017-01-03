<?php

namespace dlds\components\validators\isocode;

class PhoneNumber extends ISOValidator
{

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validateValue($value)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {
            $number = $phoneUtil->parse($value, 'CZ');

            if ($phoneUtil->isValidNumber($number)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $ex) {
            return false;
        }
    }

}
