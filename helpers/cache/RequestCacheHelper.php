<?php

namespace dlds\components\helpers\cache;

use yii\helpers\ArrayHelper;

class RequestCacheHelper {
    /**
     * QueryCache prefixes
     */
    const PREFIX_QUERY_PARAMS = 'query';

    /**
     * Defaults
     */
    const DEFAULT_QUERY_VALUE = [];

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
            self::clear($key);
        }

        return $value ? $value : self::DEFAULT_QUERY_VALUE;
    }

    /**
     * Clears cached query params
     * @param string $route given route
     */
    public static function clearQueryParams($route)
    {
        $key = self::getKey($route, self::PREFIX_QUERY_PARAMS);

        self::clear($key);
    }

    /**
     * Retrieves query params based on conditions below
     * - If $clearParam is occured in $params, cached query is cleared and default value is retrieved
     * - If $formParam is occured in $params than $params are cached and retrieved
     * - Else cached query is retrieves
     * @param string $route given route
     * @param array $params given query params
     * @param string $formParam query parameter representing form
     * @param string $clearParam parameter representing clearing
     * @return array query params
     */
    public static function processQuery($route, array $params, $formParam, $clearParam = false)
    {
        if ($clearParam && ArrayHelper::getValue($params, $clearParam, false))
        {
            self::clearQueryParams($route);
            
            return self::DEFAULT_QUERY_VALUE;
        }

        if (ArrayHelper::getValue($params, $formParam, false))
        {
            self::setQueryParams($route, $params);

            return $params;
        }

        return self::getQueryParams($route);
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

    /**
     * Cleares cache with given key
     * @param string $key chace key
     */
    protected static function clear($key)
    {
        return \Yii::$app->cache->delete($key);
    }
}