<?php

namespace dlds\components\validators\isocode;

class OrganismeType12NormeB2 extends ISOValidator {

    const DEFAULT_CLEF = -1;

    /**
     * @inheritdoc
     */
    public function validateValue($value, $params = [])
    {
        $clef = \yii\helpers\ArrayHelper::getValue($params, 'clef', self::DEFAULT_CLEF);

        if (strlen($clef) < 1)
        {
            return false;
        }

        if (!is_numeric($clef))
        {
            return false;
        }

        if (!is_string($value))
        {
            return false;
        }

        if (strlen($value) < 2)
        {
            return false;
        }

        $chiffres = str_split($value);
        $rang = array_reverse(array_keys($chiffres));
        $chiffresOrdonnes = array();
        foreach ($rang as $i => $valeurRang)
        {
            $chiffresOrdonnes[$valeurRang + 1] = $chiffres[$i];
        }
        $resultats = array();
        foreach ($chiffresOrdonnes as $cle => $valeur)
        {
            $resultats[$valeur] = ($cle % 2 == 0) ? ($valeur * 1) : ($valeur * 2);
        }
        $addition = 0;
        foreach ($resultats as $cle => $valeur)
        {
            $addition += array_sum(str_split($valeur));
        }
        $clefValide = str_split($addition);
        $clefValide = 10 - array_pop($clefValide);

        return ($clef === $clefValide);
    }

}
