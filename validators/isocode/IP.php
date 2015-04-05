<?php

namespace dlds\components\validators\isocode;

class IP extends ISOValidator {

    /**
     * Versions
     */
    const VERSION_IPV4 = FILTER_FLAG_IPV4;
    const VERSION_IPV6 = FILTER_FLAG_IPV6;

    /**
     * @var int version flag
     */
    public $version = self::VERSION_IPV4;

    /**
     * IPV4 public-only validator
     *
     * @param string $value
     *
     * @link http://php.net/manual/fr/function.filter-var.php
     *
     * @return boolean
     */
    public function validateValue($value)
    {
        return (false !== filter_var($value, FILTER_VALIDATE_IP, $this->version));
    }

}
