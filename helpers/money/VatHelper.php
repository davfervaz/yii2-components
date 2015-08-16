<?php

namespace dlds\components\helpers\money;

use yii\helpers\ArrayHelper;

class VatHelper {

    /**
     * @var array hold vat rations to sub given vat from value
     */
    protected static $ratios = [
        '21' => 0.1736,
    ];

    /**
     * Adds vat to given price and retrieves price with vat
     * @param float $priceWithoutVat given price without vat
     * @param float $vat vat percentage value to be added
     */
    public static function addVat($priceWithoutVat, $vat)
    {
        if ($vat)
        {
            return $priceWithoutVat * (1 + ($vat / 100));
        }

        return $priceWithoutVat;
    }

    /**
     * Removes vat from given price and retrieves price without vat
     * @param float $priceWithVat given price with vat
     * @param float $vat vat percentage value to be removed (21% => 21)
     */
    public static function subVat($priceWithVat, $vat)
    {
        if ($vat)
        {
            $ratio = ArrayHelper::getValue(self::$ratios, (string) $vat, false);

            if ($ratio)
            {
                return $priceWithVat - ($priceWithVat * $ratio);
            }

            return $priceWithVat / (1 + $vat / 100);
        }

        return $priceWithVat;
    }
}