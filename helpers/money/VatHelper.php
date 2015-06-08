<?php

namespace dlds\components\helpers\money;

class VatHelper {

    /**
     * Adds vat to given price and retrieves price with vat
     * @param float $priceWithoutVat given price without vat
     * @param float $vat vat percentage value to be added
     */
    public static function addVat($priceWithoutVat, $vat)
    {
        if ($vat)
        {
            return $priceWithoutVat * (1 + $vat / 100);
        }

        return $priceWithoutVat;
    }

    /**
     * Removes vat from given price and retrieves price without vat
     * @param float $priceWithVat given price with vat
     * @param float $vat vat percentage value to be removed
     */
    public static function subVat($priceWithVat, $vat)
    {
        if ($vat)
        {
            return $priceWithVat / (1 + $vat / 100);
        }

        return $priceWithVat;
    }
}