<?php

namespace dlds\components\handlers\widgets;

use yii\helpers\ArrayHelper;

class MenuHandler {

    /**
     * @var boolean default menu id
     */
    private static $_default;

    /**
     * Process menu rendering
     * @param array $config
     */
    public static function process($config)
    {
        $route = \Yii::$app->requestedRoute;

        foreach ($config as $id => $rules)
        {
            $regExp = ArrayHelper::getValue($rules, 0, false);

            if (!is_string($regExp) && !is_bool($regExp))
            {
                throw new \yii\base\ErrorException('Invalid menu RegExp. RegExp should be valid reqular expresion or boolean.');
            }

            $items = ArrayHelper::getValue($rules, 1, []);

            if (!is_array($items))
            {
                throw new \yii\base\ErrorException('Invalid menu items. Items should be array.');
            }

            if (true === $regExp)
            {
                self::$_default = $items;
            }
            elseif (preg_match($regExp, $route))
            {
                return $items;
            }
        }

        return (self::$_default) ? self::$_default : [];
    }

}
