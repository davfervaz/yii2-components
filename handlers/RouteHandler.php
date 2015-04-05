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

        foreach ($config as $rules)
        {
            $regexps = ArrayHelper::getValue($rules, 0, false);

            if (!is_array($regexps) && !is_bool($regexps))
            {
                throw new \yii\base\ErrorException('Invalid config. RegEx should be passed as array or boolean.');
            }

            $callback = ArrayHelper::getValue($rules, 1, []);

            if ($run && !is_callable($callback))
            {
                throw new \yii\base\ErrorException('Invalid callback. Callable function must be provided when run property is set to true.');
            }

            if (true === $regexps)
            {
                self::$_default = $callback;
            }
            else
            {
                foreach ($regexps as $regex)
                {
                    if (preg_match($regex, $route))
                    {
                        return ($run) ? call_user_func($callback) : $callback;
                    }
                }
            }
        }

        return ($run && self::$_default) ? call_user_func(self::$_default) : self::$_default;
    }

}
