<?php

namespace dlds\components\handlers;

use yii\helpers\ArrayHelper;

class RouteHandler {

    /**
     * @var boolean default menu id
     */
    private static $_default;

    /**
     * Process menu rendering
     * @param array $config
     * @param boolean $run indicated if given callback should be retrieved or run
     */
    public static function detect($config, $run = false)
    {
        $route = \Yii::$app->requestedRoute;

        foreach ($config as $id => $rules)
        {
            $regExp = ArrayHelper::getValue($rules, 0, false);

            if (!is_string($regExp) && !is_bool($regExp))
            {
                throw new \yii\base\ErrorException('Invalid menu RegExp. RegExp should be valid reqular expresion or boolean.');
            }

            $callback = ArrayHelper::getValue($rules, 1, []);

            if ($run && !is_callable($callback))
            {
                throw new \yii\base\ErrorException('Invalid callback. Callable function must be provided when run property is set to true.');
            }

            if (true === $regExp)
            {
                self::$_default = $callback;
            }
            elseif (preg_match($regExp, $route))
            {
                return ($run) ? call_user_func($callback) : $callback;
            }
        }

        return ($run && self::$_default) ? call_user_func(self::$_default) : self::$_default;
    }

}
