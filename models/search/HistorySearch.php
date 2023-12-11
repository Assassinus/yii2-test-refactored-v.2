<?php

namespace app\models\search;

use app\models\History;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Exception;

/**
 * HistorySearch represents the model behind the search form about `app\models\History`.
 *
 * @property array $objects
 */
class HistorySearch extends History implements IHistorySearch
{
    public const OPTIMAL_BATCH_SIZE = 1600;
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = History::find();

        $this->load($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }

        $query->addSelect('history.*');
        $query->with([
            'customer',
            'user',
            'sms',
            'task',
            'call',
            'fax',
        ])->batch(self::OPTIMAL_BATCH_SIZE);

        $dataProvider->setSort([
            'defaultOrder' => [
                'ins_ts' => SORT_DESC,
                'id'     => SORT_DESC
            ],
        ]);

        return $dataProvider;
    }

    /**
     * @param $batchSize
     * @return ?array
     * @throws Exception
     */
    public function vanillaSqlSearch($batchSize): ?array
    {
        $batch = 1;
        $mergerQueryResult = [];

        do {
            $offset = ($batch - 1) * $batchSize;
            $query = <<<SQL
SELECT history.ins_ts,
                IFNULL(user.username, 'System')   AS username,
                history.object,
                history.event,
                IF(history.object = 'task', task.title, sms.message) AS message
FROM history
         LEFT JOIN user ON history.user_id = user.id
         LEFT JOIN sms ON history.object = 'sms' AND history.object_id = sms.id 
         LEFT JOIN task ON  history.object = 'task' AND history.object_id = task.id
SQL;

            $query .= ' LIMIT ' . $batchSize . " OFFSET " . $offset;
            $queryResult = Yii::$app->db->createCommand($query)->queryAll();

            if (!empty($queryResult)) {
                $mergerQueryResult = array_merge($mergerQueryResult, $queryResult);
            }

            $batch++;
        } while (!empty($queryResult));

        return $mergerQueryResult;
    }
}