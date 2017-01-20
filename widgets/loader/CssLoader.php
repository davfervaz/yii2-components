<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\components\widgets\loader;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * CssLoader renders single CSS loader element
 *
 * Based on Connor Atheron's css pack
 * @see https://github.com/ConnorAtherton/loaders.css
 * @author Jirka Svoboda<jiri.svoboda@dlds.cz>
 */
class CssLoader extends \yii\base\Widget
{

    /**
     * Available types
     */
    const T_BALL_BEAT = 'ball-beat';
    const T_BALL_CLIP_ROTATE = 'ball-clip-rotate';
    const T_BALL_CLIP_ROTATE_PULSE = 'ball-clip-rotate-pulse';
    const T_BALL_CLIP_ROTATE_MULTIPLE = 'ball-clip-rotate-multiple';
    const T_BALL_GRID_PULSE = 'ball-grid-pulse';
    const T_BALL_GRID_BEAT = 'ball-grid-beat';
    const T_BALL_PULSE = 'ball-pulse';
    const T_BALL_PULSE_RISE = 'ball-pulse-rise';
    const T_BALL_PULSE_SYNC = 'ball-pulse-sync';
    const T_BALL_ROTATE = 'ball-rotate';
    const T_BALL_SCALE = 'ball-scale';
    const T_BALL_SCALE_MULTIPLE = 'ball-scale-multiple';
    const T_BALL_SCALE_RANDOM = 'ball-scale-random';
    const T_BALL_SCALE_RIPPLE = 'ball-scale-ripple';
    const T_BALL_SCALE_RIPPLE_MULTIPE = 'ball-scale-ripple-multiple';
    const T_BALL_SPIN_FADE = 'ball-spin-fade-loader';
    const T_BALL_TRIANGLE_PATH = 'ball-triangle-path';
    const T_BALL_ZIG_ZAG = 'ball-zig-zag';
    const T_BALL_ZIG_ZAG_DEFLECT = 'ball-zig-zag-deflect';
    const T_CUBE_TRANSITION = 'cube-transition';
    const T_LINE_SCALE = 'line-scale';
    const T_LINE_SCALE_PARTY = 'line-scale-party';
    const T_LINE_SCALE_PULSE_OUT = 'line-scale-pulse-out';
    const T_LINE_SCALE_PULSE_OUT_RAPID = 'line-scale-pulse-out-rapid';
    const T_LINE_SPIN_FADE = 'line-spin-fade-loader';
    const T_PACMAN = 'pacman';
    const T_SEMI_CIRCLE_SPIN = 'semi-circle-spin';
    const T_SQUARE_SPIN = 'square-spin';
    const T_TRIANGLE_SKEW_SPIN = 'triangle-skew-spin';

    /**
     * Available sizes
     */
    const S_XS = 'sm';
    const S_MD = 'md';
    const S_LG = 'lg';

    /**
     * @var int spiiner type
     */
    public $type = self::T_BALL_CLIP_ROTATE_MULTIPLE;

    /**
     * @var boolean indicates if loader size is size
     */
    public $size = self::S_MD;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();

        Html::addCssClass($this->options, $this->elmClass());

        $html = Html::beginTag('div', $this->options);

        for ($i = 1; $i <= $this->elmChilds($this->type); $i++) {
            $html .= Html::tag('div', '');
        }

        $html .= Html::endTag('div');

        echo $html;
    }

    /**
     * Registeres appropriate assets
     */
    protected function registerAssets()
    {
        switch ($this->type) {
            case self::T_BALL_CLIP_ROTATE_MULTIPLE:
                bundles\BallClipRotateMultipleAsset::register($this->view, $this->type);
                break;
            default:
                throw new \yii\base\NotSupportedException('AssetBundle is not supported yet for this type.');
        }
    }

    /**
     * Retrieves animation parent element class
     * @return string
     */
    protected function elmClass()
    {
        if ($this->size) {
            return sprintf('cl-indicator cl-%s cl-%s', $this->type, $this->size);
        }

        return $this->type;
    }

    /**
     * Retrieves animation configs
     * @return array
     */
    protected function elmChilds($type)
    {
        $config = [
            self::T_BALL_BEAT => 3,
            self::T_BALL_CLIP_ROTATE => 1,
            self::T_BALL_CLIP_ROTATE_PULSE => 2,
            self::T_BALL_CLIP_ROTATE_MULTIPLE => 2,
            self::T_BALL_GRID_PULSE => 9,
            self::T_BALL_GRID_BEAT => 9,
            self::T_BALL_PULSE => 3,
            self::T_BALL_PULSE_RISE => 5,
            self::T_BALL_PULSE_SYNC => 3,
            self::T_BALL_ROTATE => 1,
            self::T_BALL_SCALE => 1,
            self::T_BALL_SCALE_MULTIPLE => 3,
            self::T_BALL_SCALE_RANDOM => 3,
            self::T_BALL_SCALE_RIPPLE => 1,
            self::T_BALL_SCALE_RIPPLE_MULTIPE => 3,
            self::T_BALL_SPIN_FADE => 8,
            self::T_BALL_TRIANGLE_PATH => 3,
            self::T_BALL_ZIG_ZAG => 2,
            self::T_BALL_ZIG_ZAG_DEFLECT => 2,
            self::T_CUBE_TRANSITION => 2,
            self::T_LINE_SCALE => 5,
            self::T_LINE_SCALE_PARTY => 4,
            self::T_LINE_SCALE_PULSE_OUT => 5,
            self::T_LINE_SCALE_PULSE_OUT_RAPID => 5,
            self::T_LINE_SPIN_FADE => 8,
            self::T_PACMAN => 5,
            self::T_SEMI_CIRCLE_SPIN => 1,
            self::T_SQUARE_SPIN => 1,
            self::T_TRIANGLE_SKEW_SPIN => 1,
        ];

        return ArrayHelper::getValue($config, $type, 1);
    }

}
