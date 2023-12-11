<?php

namespace app\widgets\HistoryList;

use app\components\export\ExportTypes;
use app\models\search\HistorySearch;
use app\services\history\event\IEventService;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\di\NotInstantiableException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

class HistoryList extends Widget
{
    public function __construct(
        private readonly HistorySearch $historySearch
    )
    {
        parent::__construct();
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function run(): string
    {
        $eventService = Yii::$container->get(IEventService::class);

        return $this->render('main', [
            'dataProvider' => $this->historySearch->search(Yii::$app->request->queryParams),
            'linkExport'   => $this->getLinkExport(),
            'eventService' => $eventService
        ]);
    }

    /**
     * @return string
     */
    private function getLinkExport(): string
    {
        $params = Yii::$app->getRequest()->getQueryParams();

        $params = array_filter($params, fn($value) => is_string($value) || is_numeric($value));
        $params = array_map(fn($value) => is_string($value) ? Html::encode($value) : $value, $params);

        $params = ArrayHelper::merge(['exportType' => ExportTypes::FORMAT_CSV], $params);
        $params[0] = '/export';

        return Url::to($params);
    }
}
