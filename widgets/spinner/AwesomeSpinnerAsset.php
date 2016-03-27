<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\components\widgets\spinner;

use yii\web\AssetBundle;

/**
 * @author Jiri Svoboda <jiri.svobodao@dlds.cz>
 * @package intercooler
 * @see http://intercoolerjs.org/docs.html
 */
class AwesomeSpinnerAsset extends AssetBundle {

    public $sourcePath = '@dlds/components/widgets/spinner/assets';
    public $css = [
        'spinner.css',
    ];

    public function init()
    {
        parent::init();
    }
}