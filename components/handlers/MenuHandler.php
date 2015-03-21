<?php

namespace dlds\menu\components\handlers;

use yii\helpers\ArrayHelpers;

class MenuHandler {

    /**
     * @var array given menu rules
     */
    public $rules;

    /**
     * @var array given menu items
     */
    public $items;

    /**
     * Process menu rendering
     * @param array $config
     */
    public static function process($config)
    {
        $this->rules = ArrayHelper::getValue($config, 'rules', []);
        $this->items = ArrayHelper::getValue($config, 'items', []);

        if (!$this->rules)
        {
            return $this->items;
        }
    }

}
