<?php

namespace dlds\components\validators\isocode;

use common\modules\core\models\db\CoreCountry;
use yii\base\UnknownPropertyException;

class PhoneNumber extends ISOValidator
{
    const DEFAULT_REGION = 'CZ';


    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {

        $codeAttribute = sprintf("%s_%s", $attribute, 'code');
        $defaultRegion = self::DEFAULT_REGION;

        try {
            $coreCountryId = $model->$codeAttribute;
            $coreCountryRow = CoreCountry::findOne($coreCountryId);
            if ($coreCountryRow) {
                $defaultRegion = $coreCountryRow->iso;
            }
        } catch (UnknownPropertyException $e) {

        }

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {
            $number = $phoneUtil->parse($model->$attribute, $defaultRegion);

            if ($phoneUtil->isValidNumber($number)) {
                $isValid = true;
            } else {
                $isValid = false;
            }
        } catch (\Exception $ex) {
            $isValid = false;
        }

        if (!$isValid) {
            $this->addError($model, $attribute, $this->message);
        }
    }


}
