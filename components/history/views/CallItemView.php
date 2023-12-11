<?php

namespace app\components\history\views;

use app\components\history\HistoryListRenderer;
use app\components\history\IEventItemView;
use app\models\Call;
use app\models\History;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class CallItemView implements IEventItemView
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

        return $historyListRenderer->render('@historyList/_item_common.php', $this->getExtendedParams($params));
    }

    /**
     * @param $params
     * @return array
     */
    private function getExtendedParams($params): array
    {
        return
            [
                ...$params,
                'content'        => $call->comment ?? '',
                'footerDatetime' => $this->history->ins_ts,
                'iconClass'      => $this->history->call?->isAnswered() ? 'md-phone bg-green' : 'md-phone-missed bg-red',
                'iconIncome'     => $this->history->call?->isAnswered() && $this->history->call?->direction == Call::DIRECTION_INCOMING
            ];
    }
}