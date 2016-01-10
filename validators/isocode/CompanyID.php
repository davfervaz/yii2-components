<?php

namespace dlds\components\validators\isocode;

class CompanyID extends ISOValidator {

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
    public $country = self::COUNTRY_CZ;


    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        $methodName = 'validate' . trim(ucfirst(strtolower($this->country)));

        if (!is_callable([__CLASS__, $methodName]))
        {
            throw new \InvalidArgumentException("ERROR: The CompanyID validator for $this->country does not exists yet: feel free to add it.");
        }

        return call_user_func_array([__CLASS__, $methodName], [$value]);
    }

    /**
     * Validates CZ Company ID
     * @param type $value
     * @return boolean
     */
    protected function validateCZ($value)
    {
        // "be liberal in what you receive"
        $value = preg_replace('#\s+#', '', $value);

        // má požadovaný tvar?
        if (!preg_match('#^\d{8}$#', $value))
        {
            return FALSE;
        }

        // kontrolní součet
        $a = 0;
        for ($i = 0; $i < 7; $i++)
        {
            $a += $value[$i] * (8 - $i);
        }

        $a = $a % 11;

        if ($a === 0)
            $c = 1;
        elseif ($a === 10)
            $c = 1;
        elseif ($a === 1)
            $c = 0;
        else
            $c = 11 - $a;

        return (int) $value[7] === $c;
    }

}
