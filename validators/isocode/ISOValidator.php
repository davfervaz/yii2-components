<?php

namespace dlds\components\validators\isocode;

use yii\validators\Validator;

class ISOValidator extends Validator {

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->message)
        {
            $this->message = \Yii::t('app', 'Given value has bad format');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $isValid = $this->validateValue($model->$attribute);

        if (!$isValid)
        {
            $this->addError($model, $attribute, $this->message);
        }
    }

    /**
     * Return secured value
     * @param type $value
     * @return type
     */
    protected function secureValue($value)
    {
        return trim($value);
    }
}