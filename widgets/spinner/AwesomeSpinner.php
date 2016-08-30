<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\components\widgets\spinner;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * AwesomeSpinner renders single element CSS spinners
 *
 * Based on Daiel Cardoso's css pack
 * @see https://github.com/danielcardoso/load-awesome
 * @author
 */
class AwesomeSpinner extends \yii\base\Widget
{

    /**
     * CSS Prefix
     */
    const CSS_PREFIX = 'la';

    /**
     * Animation types
     */
    const TYPE_BALL_8BITS = 'ball-8bits';
    const TYPE_BALL_ATOM = 'ball-atom';
    const TYPE_BALL_BEAT = 'ball-beat';
    const TYPE_BALL_CIRCUS = 'ball-circus';
    const TYPE_BALL_CLIMBING_DOT = 'ball-climbing-dot';
    const TYPE_BALL_CLIP_ROTATE = 'ball-clip-rotate';
    const TYPE_BALL_CLIP_ROTATE_PULSE = 'ball-clip-rotate-pulse';
    const TYPE_BALL_CLIP_ROTATE_MULTIPLE = 'ball-clip-rotate-multiple';
    const TYPE_BALL_ELASTIC_DOTS = 'ball-elastic-dots';
    const TYPE_BALL_FALL = 'ball-fall';
    const TYPE_BALL_FUSSION = 'ball-fussion';
    const TYPE_BALL_GRID_BEAT = 'ball-grid-beat';
    const TYPE_BALL_GRID_PULSE = 'ball-grid-pulse';
    const TYPE_BALL_NEWTON_CRADLE = 'ball-newton-cradle';
    const TYPE_BALL_PULSE = 'ball-pulse';
    const TYPE_BALL_PULSE_RISE = 'ball-pulse-rise';
    const TYPE_BALL_PULSE_SYNC = 'ball-pulse-sync';
    const TYPE_BALL_ROTATE = 'ball-rotate';
    const TYPE_BALL_RUNNING_DOTS = 'ball-running-dots';
    const TYPE_BALL_SCALE = 'ball-scale';
    const TYPE_BALL_SCALE_PULSE = 'ball-scale-pulse';
    const TYPE_BALL_SCALE_MULTIPLE = 'ball-scale-multiple';
    const TYPE_BALL_SCALE_RIPPLE = 'ball-scale-ripple';
    const TYPE_BALL_SCALE_RIPPLE_MULTIPE = 'ball-scale-ripple-multiple';
    const TYPE_BALL_SPIN = 'ball-spin';
    const TYPE_BALL_SPIN_CLOCKWISE = 'ball-spin-clockwise';
    const TYPE_BALL_SPIN_CLOCKWISE_FADE = 'ball-spin-clockwise-fade';
    const TYPE_BALL_SPIN_CLOCKWISE_FADE_ROTATING = 'ball-spin-clockwise-fade-rotating';
    const TYPE_BALL_SPIN_FADE = 'ball-spin-fade';
    const TYPE_BALL_SPIN_FADE_ROTATING = 'ball-spin-fade-rotating';
    const TYPE_BALL_SPIN_ROTATE = 'ball-spin-rotate';
    const TYPE_BALL_SQUARE_SPIN = 'ball-square-spin';
    const TYPE_BALL_SQUARE_CLOCKWISE_SPIN = 'ball-square-clockwise-spin';
    const TYPE_BALL_TRIANGLE_PATH = 'ball-triangle-path';
    const TYPE_BALL_ZIG_ZAG = 'ball-zig-zag';
    const TYPE_BALL_ZIG_ZAG_DEFLECT = 'ball-zig-zag-deflect';
    const TYPE_COG = 'cog';
    const TYPE_CUBE_TRANSITION = 'cube-transition';
    const TYPE_FIRE = 'fire';
    const TYPE_LINE_SCALE = 'line-scale';
    const TYPE_LINE_SCALE_PARTY = 'line-scale-party';
    const TYPE_LINE_SCALE_PULSE_OUT = 'line-scale-pulse-out';
    const TYPE_LINE_SCALE_PULSE_OUT_RAPID = 'line-scale-pulse-out-rapid';
    const TYPE_LINE_SPIN_FADE = 'line-spin-fade';
    const TYPE_LINE_SPIN_FADE_ROTATING = 'line-spin-fade-rotating';
    const TYPE_LINE_SPIN_CLOCKWISE_FADE = 'line-spin-clockwise-fade';
    const TYPE_LINE_SPIN_CLOCKWISE_FADE_ROTATING = 'line-spin-clockwise-fade-rotating';
    const TYPE_PACMAN = 'pacman';
    const TYPE_SQUARE_SPIN = 'square-spin';
    const TYPE_SQUARE_LOADER = 'square-loader';
    const TYPE_SQUARE_JELLY_BOX = 'square-jelly-box';
    const TYPE_TIMER = 'timer';
    const TYPE_TRIANGLE_SKEW_SPIN = 'triangle-skew-spin';

    /**
     * @var int spiiner type
     */
    public $type = self::TYPE_LINE_SCALE;

    /**
     * @var boolean indicates if loader size is doubled
     */
    public $doubled = false;

    /**
     * @var string spinner tag name
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();

        Html::addCssClass($this->options, $this->getAnimationClass());

        $html = Html::beginTag('div', $this->options);

        for ($i = 1; $i <= $this->getAnimationChilds(); $i++) {
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
            case self::TYPE_BALL_ATOM:
                bundles\BallAtomAsset::register($this->view, $this->type);
                break;
            case self::TYPE_BALL_BEAT:
                bundles\BallBeatAsset::register($this->view, $this->type);
                break;
            case self::TYPE_BALL_CIRCUS:
                bundles\BallCircusAsset::register($this->view, $this->type);
                break;
            case self::TYPE_BALL_CLIP_ROTATE:
                bundles\BallClipRotateAsset::register($this->view, $this->type);
                break;
            case self::TYPE_BALL_CLIP_ROTATE_MULTIPLE:
                bundles\BallClipRotateMultipleAsset::register($this->view, $this->type);
                break;
            case self::TYPE_BALL_CLIP_ROTATE_PULSE:
                bundles\BallClipRotatePulseAsset::register($this->view, $this->type);
                break;
            case self::TYPE_BALL_SPIN_CLOCKWISE:
                bundles\BallSpinClockwiseAsset::register($this->view, $this->type);
                break;
            case self::TYPE_LINE_SCALE:
                bundles\LineScaleAsset::register($this->view, $this->type);
                break;
            case self::TYPE_TIMER:
                bundles\TimerAsset::register($this->view, $this->type);
                break;
            default:
                throw new \yii\base\NotSupportedException('AssetBundle is not supported yet for this type.');
        }
    }

    /**
     * Retrieves animation parent element class
     * @return string
     */
    protected function getAnimationClass()
    {
        if ($this->doubled) {
            return sprintf('%s-%s %s-2x', self::CSS_PREFIX, $this->type, self::CSS_PREFIX);
        }

        return sprintf('%s-%s', self::CSS_PREFIX, $this->type);
    }

    /**
     * Retrieves animation configs
     * @return array
     */
    protected function getAnimationChilds()
    {
        $config = [
            self::TYPE_BALL_8BITS => 16,
            self::TYPE_BALL_ATOM => 4,
            self::TYPE_BALL_BEAT => 3,
            self::TYPE_BALL_CIRCUS => 5,
            self::TYPE_BALL_CLIMBING_DOT => 4,
            self::TYPE_BALL_CLIP_ROTATE => 1,
            self::TYPE_BALL_CLIP_ROTATE_PULSE => 2,
            self::TYPE_BALL_CLIP_ROTATE_MULTIPLE => 2,
            self::TYPE_BALL_ELASTIC_DOTS => 5,
            self::TYPE_BALL_FALL => 3,
            self::TYPE_BALL_FUSSION => 4,
            self::TYPE_BALL_GRID_BEAT => 9,
            self::TYPE_BALL_GRID_PULSE => 9,
            self::TYPE_BALL_NEWTON_CRADLE => 4,
            self::TYPE_BALL_PULSE => 3,
            self::TYPE_BALL_PULSE_RISE => 5,
            self::TYPE_BALL_PULSE_SYNC => 3,
            self::TYPE_BALL_ROTATE => 1,
            self::TYPE_BALL_RUNNING_DOTS => 5,
            self::TYPE_BALL_SCALE => 1,
            self::TYPE_BALL_SCALE_PULSE => 2,
            self::TYPE_BALL_SCALE_MULTIPLE => 3,
            self::TYPE_BALL_SCALE_RIPPLE => 1,
            self::TYPE_BALL_SCALE_RIPPLE_MULTIPE => 3,
            self::TYPE_BALL_SPIN => 8,
            self::TYPE_BALL_SPIN_CLOCKWISE => 8,
            self::TYPE_BALL_SPIN_CLOCKWISE_FADE => 8,
            self::TYPE_BALL_SPIN_CLOCKWISE_FADE_ROTATING => 8,
            self::TYPE_BALL_SPIN_FADE => 8,
            self::TYPE_BALL_SPIN_FADE_ROTATING => 8,
            self::TYPE_BALL_SPIN_ROTATE => 2,
            self::TYPE_BALL_SQUARE_SPIN => 8,
            self::TYPE_BALL_SQUARE_CLOCKWISE_SPIN => 8,
            self::TYPE_BALL_TRIANGLE_PATH => 3,
            self::TYPE_BALL_ZIG_ZAG => 2,
            self::TYPE_BALL_ZIG_ZAG_DEFLECT => 2,
            self::TYPE_COG => 1,
            self::TYPE_CUBE_TRANSITION => 2,
            self::TYPE_FIRE => 3,
            self::TYPE_LINE_SCALE_PARTY => 1,
            self::TYPE_LINE_SCALE_PULSE_OUT => 5,
            self::TYPE_LINE_SCALE_PULSE_OUT_RAPID => 5,
            self::TYPE_LINE_SCALE => 5,
            self::TYPE_LINE_SPIN_FADE => 8,
            self::TYPE_LINE_SPIN_FADE_ROTATING => 8,
            self::TYPE_LINE_SPIN_CLOCKWISE_FADE => 8,
            self::TYPE_LINE_SPIN_CLOCKWISE_FADE_ROTATING => 8,
            self::TYPE_PACMAN => 6,
            self::TYPE_SQUARE_SPIN => 1,
            self::TYPE_SQUARE_LOADER => 1,
            self::TYPE_SQUARE_JELLY_BOX => 2,
            self::TYPE_TIMER => 1,
            self::TYPE_TRIANGLE_SKEW_SPIN => 1,
        ];

        return ArrayHelper::getValue($config, $this->type, 1);
    }

}
