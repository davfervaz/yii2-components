<?php

namespace dlds\components\behaviors\datetime;

use yii\base\Behavior;
use yii\helpers\ArrayHelper;

class DateTimeConvertBehavior extends Behavior
{
    /**
     * Config names
     */
    const CN_FORMAT_DISPLAY = 'cn_format_display';
    const CN_FORMAT_SAVE = 'cn_format_save';

    /**
     * @var array config
     */
    public $config;

    /**
     * @var string
     */
    public $timezone = 'Europe/Prague';

    /**
     * @var string
     */
    public $defaultFormatDisplay = 'd.m.Y H:i:s';

    /**
     * @var string
     */
    public $defaultFormatSave = 'U';

    /**
     * Validates handle attributes convert before validation
     */
    public function events()
    {
        return [
            \yii\db\ActiveRecord::EVENT_BEFORE_VALIDATE => 'handleValidate',
            \yii\db\ActiveRecord::EVENT_AFTER_INSERT => 'handleRead',
            \yii\db\ActiveRecord::EVENT_AFTER_UPDATE => 'handleRead',
            \yii\db\ActiveRecord::EVENT_AFTER_REFRESH => 'handleRead',
            \yii\db\ActiveRecord::EVENT_AFTER_FIND => 'handleRead',
        ];
    }

    /**
     * Handle save convert
     */
    public function handleValidate()
    {
        foreach ($this->attrs() as $attr) {
            $this->owner->$attr = static::convert($this->owner->$attr, $this->formatToDisplay($attr), $this->formatToSave($attr), $this->timezone);
        }
    }

    /**
     * Handles read convert
     */
    public function handleRead()
    {
        foreach ($this->attrs() as $attr) {
            $this->owner->$attr = static::convert($this->owner->$attr, $this->formatToSave($attr), $this->formatToDisplay($attr), $this->timezone);
        }
    }

    /**
     * Converts given value from input format to output format
     * @param $value
     * @param $inputFormat
     * @param $outputFormat
     * @return string
     */
    public static function convert($value, $inputFormat, $outputFormat, $timezone)
    {
        $dt = \DateTime::createFromFormat($inputFormat, $value);

        if (!$dt) {
            return false;
        }

        $dt->setTimezone(new \DateTimeZone($timezone));

        return $dt->format($outputFormat);
    }

    /**
     * Retrieves converter attributes names
     * @return array
     */
    protected function attrs()
    {
        if (!ArrayHelper::isAssociative($this->config)) {
            return $this->config;
        }

        return array_keys($this->config);
    }

    /**
     * Retrieves attribute config
     * @return array
     */
    protected function attrConfig($attrName, $configName)
    {
        $attrConfig = ArrayHelper::getValue($this->config, $attrName, false);

        if (!$attrConfig) {
            return false;
        }

        return ArrayHelper::getValue($attrConfig, $configName, false);
    }

    /**
     * Retrieves format for displaying value
     * @param $attr
     * @return mixed|string
     */
    protected function formatToDisplay($attr)
    {
        $format = $this->attrConfig($attr, self::CN_FORMAT_DISPLAY);

        if (!$format) {
            return $this->defaultFormatDisplay;
        }

        return $format;
    }

    /**
     * Retrieves format for saving value
     * @param $attr
     * @return mixed|string
     */
    protected function formatToSave($attr)
    {
        $format = $this->attrConfig($attr, self::CN_FORMAT_SAVE);

        if (!$format) {
            return $this->defaultFormatSave;
        }

        return $format;
    }

}