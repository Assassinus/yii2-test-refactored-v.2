<?php

namespace app\components\history\views;

use app\components\history\HistoryListRenderer;
use app\components\history\IEventItemView;
use app\models\History;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class TaskItemView implements IEventItemView
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
        $renderer = \Yii::$container->get(HistoryListRenderer::class);

        return $renderer->render('@historyList/_item_common.php', $this->getExtendedParams($params));
    }

    /**
     * @param $params
     * @return array
     */
    private function getExtendedParams($params): array
    {
        return [
            ...$params,
            'iconClass'      => 'fa-check-square bg-yellow',
            'footerDatetime' => $this->history->ins_ts,
        ];
    }
}