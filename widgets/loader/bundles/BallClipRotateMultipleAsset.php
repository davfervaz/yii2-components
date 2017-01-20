<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\components\widgets\loader\bundles;

use yii\web\AssetBundle;

/**
 * @author Jiri Svoboda <jiri.svobodao@dlds.cz>
 * @package loader
 */
class BallClipRotateMultipleAsset extends AssetBundle
{

    public $sourcePath = '@dlds/components/widgets/loader/assets';

    /**
     * @var array
     */
    public $css = [
        'ball-clip-rotate-multiple.css',
    ];

}
