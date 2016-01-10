<?php

namespace dlds\components\helpers\data;

class DataProviderHelper extends \common\components\helpers\DataProviderHelper {

    /**
     * Sets globally default sort
     * @param \yii\data\ActiveDataProvider $dataProvider
     */
    public static function setDefaultSort(\yii\data\ActiveDataProvider &$dataProvider, array $sort)
    {
        $dataProvider->setSort([
            'defaultOrder' => $sort,
        ]);
    }

    /**
     * Sets page size
     * @param \yii\data\ActiveDataProvider $dataProvider
     */
    public static function setPageSize(\yii\data\ActiveDataProvider &$dataProvider, $pageSize)
    {
        $dataProvider->setPagination([
            $dataProvider->pagination->pageSizeParam => $pageSize,
        ]);
    }

}
