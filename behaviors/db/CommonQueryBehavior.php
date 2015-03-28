<?php

namespace dlds\components\behaviors\db;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * This is the behavior class
 *
 * @property \yii\db\ActiveQuery $owner
 */
class CommonQueryBehavior extends \yii\base\Behavior {

    /**
     * Gets the root nodes.
     * @return \yii\db\ActiveQuery the owner
     */
    public function others($excluded)
    {
        if (!empty($excluded))
        {
            if (!is_array($excluded))
            {
                $excluded = [$excluded];
            }

            $pk = (new $this->owner->modelClass())->primaryKey();

            if (count($pk) > 1)
            {
                throw new \yii\base\NotSupportedException('Composite primary keys are not supported by ' . StringHelper::basename(self::className()));
            }

            $pk = array_shift($pk);

            $this->owner->andWhere(['not in', $pk, $excluded]);
        }

        return $this->owner;
    }

}
