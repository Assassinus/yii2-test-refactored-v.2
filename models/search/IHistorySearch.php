<?php

namespace app\models\search;

use yii\data\ActiveDataProvider;

interface IHistorySearch
{
    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider;

    /**
     * @param $batchSize
     * @return array|null
     */
    public function vanillaSqlSearch($batchSize): ?array;
}