<?php

namespace dlds\components\helpers\cache;

class RequestCacheHelper {

    /**
     * QueryCache prefixes
     */
    const PREFIX_QUERY_PARAMS = 'query';

    /**
     * Caches request query params for given route
     * @param string $route given rote
     * @param array $params given params
     */
    public static function setQueryParams($route, array $params)
    {
        return \Yii::$app->cache->set(self::getKey($route, self::PREFIX_QUERY_PARAMS), $params);
    }

    /**
     * Retrieves chached params for given route
     * @param string $route given route
     * @param boolean $clear indicates if params should be cleared from cache
     * @return array cached params
     */
    public static function getQueryParams($route, $clear = false)
    {
        $key = self::getKey($route, self::PREFIX_QUERY_PARAMS);

        $value = \Yii::$app->cache->get($key);

        if ($clear && $value)
        {
            \Yii::$app->cache->delete($key);
        }

        return $value ? $value : [];
    }

    /**
     * Retrieves cache key
     * @param string $name cache name
     * @param string $prefix cache prefix
     * @return string cache key
     */
    protected static function getKey($name, $prefix = null)
    {
        if (null == $prefix)
        {
            return $name;
        }

        return sprintf('%s_%s', $prefix, $name);
    }

}
