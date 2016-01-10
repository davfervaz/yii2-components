<?php

namespace dlds\components\validators\isocode;

class CreditCard extends ISOValidator {

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        if ($value === '')
        {
            return false;
        }

        //longueur de la chaine $creditCard
        $length = strlen($value);

        //resultat de l'addition de tous les chiffres
        $tot = 0;
        for ($i = $length - 1; $i >= 0; $i--)
        {
            $digit = substr($value, $i, 1);

            if ((($length - $i) % 2) == 0)
            {
                $digit = $digit * 2;
                if ($digit > 9)
                {
                    $digit = $digit - 9;
                }
            }
            $tot += (int) $digit;
        }

        return (($tot % 10) == 0);
    }

}
