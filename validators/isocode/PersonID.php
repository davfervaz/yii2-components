<?php

namespace dlds\components\validators\isocode;

class PersonID extends ISOValidator {

    /**
     * Countries
     */
    const COUNTRY_CZ = 'CZ';
    const COUNTRY_DE = 'DE';
    const COUNTRY_PL = 'PL';
    const COUNTRY_SK = 'SK';

    /**
     * @var string country
     */
    public $country = 'CZ';

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        $methodName = "validate" . trim(ucfirst(strtolower($this->country)));
        if (!is_callable([__CLASS__, $methodName]))
        {
            throw new \InvalidArgumentException("ERROR: The PersonID validator for $this->country does not exists yet: feel free to add it.");
        }

        return call_user_func_array([__CLASS__, $methodName], [$value]);
    }

    /**
     * Validates CS ID
     */
    public function validateCZ($value)
    {
        // "be liberal in what you receive"
        if (!preg_match('#^\s*(\d\d)(\d\d)(\d\d)[ /]*(\d\d\d)(\d?)\s*$#', $value, $matches))
        {
            return FALSE;
        }

        list(, $year, $month, $day, $ext, $c) = $matches;

        // do roku 1954 přidělovaná devítimístná RČ nelze ověřit
        if ($c === '')
        {
            return $year < 54;
        }

        // kontrolní číslice
        $mod = ($year . $month . $day . $ext) % 11;
        if ($mod === 10)
            $mod = 0;
        if ($mod !== (int) $c)
        {
            return FALSE;
        }

        // kontrola data
        $year += $year < 54 ? 2000 : 1900;

        // k měsíci může být připočteno 20, 50 nebo 70
        if ($month > 70 && $year > 2003)
            $month -= 70;
        elseif ($month > 50)
            $month -= 50;
        elseif ($month > 20 && $year > 2003)
            $month -= 20;

        if (!checkdate($month, $day, $year))
        {
            return FALSE;
        }

        // cislo je OK
        return TRUE;
    }

}
