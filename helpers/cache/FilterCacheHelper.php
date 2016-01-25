<?php

namespace dlds\components\helpers\cache;

use yii\helpers\ArrayHelper;
use dlds\components\interfaces\cache\CacheableFilterInterface;

class FilterCacheHelper {

    /**
     * QueryCache prefixes
     */
    const PREFIX_FILTER_PARAMS = 'filter_params';
    
    /**
     * Defaults
     */
    const DEFAULT_QUERY_VALUE = [];
    
    /**
     * Caches filter params
     * @param string $route given route
     * @param string $name given filter name
     * @param array $params given params
     */
    public static function saveParams($route, $name, array $params)
    {
        return \Yii::$app->cache->set(self::getKey($route, $name, self::PREFIX_FILTER_PARAMS), $params);
    }

    /**
     * Retrieves chached filter params
     * @param string $route given route
     * @param string $name given filter name
     * @param boolean $clear indicates if params should be cleared from cache
     * @return array cached params
     */
    public static function loadParams($route, $name, $clear = false)
    {
        $key = self::getKey($route, $name, self::PREFIX_FILTER_PARAMS);

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
    public static function clearParams($route)
    {
        $key = self::getKey($route, self::PREFIX_FILTER_PARAMS);

        self::clear($key);
    }

    /**
     * Retrieves query params based on conditions below
     * - If $clearParam is occured in $params, cached query is cleared and default value is retrieved
     * - If $formParam is occured in $params than $params are cached and retrieved
     * - Else cached query is retrieves
     * @param string $route given route
     * @param array $query given query params
     * @param string $filter query parameter representing form
     * @param string $clearParam parameter representing clearing
     * @return array query params
     */
    public static function processQuery($route, array $query, CacheableFilterInterface $filter)
    {
        $filterParams = ArrayHelper::getValue($query, $filter->getIdentification(), false);

        if (!$filterParams)
        {
            $filterParams = self::loadParams($route, $filter->getIdentification(true));
        }

        $toClear = $filter->getParamsToClear($query);

        foreach ($toClear as $attr)
        {
            unset($filterParams[$attr]);
        }

        self::saveParams($route, $filter->getIdentification(true), $filterParams);

        return [$filter->getIdentification() => $filterParams];
    }

    /**
     * Retrieves filter cache key
     * @param string $route given route
     * @param string $name filter name
     * @param string $prefix cache prefix
     * @return string cache key
     */
    protected static function getKey($route, $name, $prefix)
    {
        return sprintf('%s_%s_%s', $prefix, $route, $name);
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