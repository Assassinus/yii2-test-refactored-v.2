<?php

namespace app\components\history\views;

use app\components\history\HistoryListRenderer;
use app\components\history\IEventItemView;
use app\models\Customer;
use app\models\History;
use app\services\history\detail\DetailService;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class ChangeTypeItemView implements IEventItemView
{
    public function __construct(private readonly History $history)
    {
    }

    /**
     * @param $params
     * @return string
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function render($params): string
    {
        $historyListRenderer = \Yii::$container->get(HistoryListRenderer::class);

        return $historyListRenderer->render('@historyList/_item_statuses_change.php', $this->getExtendedParams($params));
    }

    /**
     * @param $params
     * @return array
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function getExtendedParams($params): array
    {
        $detailService = \Yii::$container->get(DetailService::class);

        return
            [
                ...$params,
                'oldValue' => Customer::getTypeTextByType($detailService->getOldValue($this->history->detail, 'type')),
                'newValue' => Customer::getTypeTextByType($detailService->getNewValue($this->history->detail, 'type'))
            ];
    }
}