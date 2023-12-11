<?php

namespace app\components\history\views;

use app\components\history\HistoryListRenderer;
use app\components\history\IEventItemView;
use app\models\History;
use app\models\Sms;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class SmsItemView implements IEventItemView
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
                'footer'         => $this->history?->sms?->direction == Sms::DIRECTION_INCOMING ?
                    \Yii::t('app', 'Incoming message from {number}', [
                        'number' => $this->history->sms->phone_from ?? ''
                    ]) : \Yii::t('app', 'Sent message to {number}', [
                        'number' => $this->history->sms->phone_to ?? ''
                    ]),
                'iconIncome'     => $this->history?->sms?->direction == Sms::DIRECTION_INCOMING,
                'footerDatetime' => $this->history->ins_ts,
                'iconClass'      => 'icon-sms bg-dark-blue'
            ];
    }
}