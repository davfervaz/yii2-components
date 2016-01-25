<?php

namespace dlds\components\interfaces\cache;

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */
interface CacheableFilterInterface {

    /**
     * Retrieves filter identification
     * @param boolean $uid indicated if uid should be included in identification
     */
    public function getIdentification($uid = false);

    /**
     * Parses given query params if everything is valid
     * @param array $params given filter params
     * @param array given request query
     */
    public function getParamsToClear($query);
}