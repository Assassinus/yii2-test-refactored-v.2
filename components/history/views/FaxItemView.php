<?php

namespace app\components\history\views;

use app\components\history\HistoryListRenderer;
use app\components\history\IEventItemView;
use app\models\History;
use app\services\history\event\IEventService;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\helpers\Html;

class FaxItemView implements IEventItemView
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
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function getExtendedParams($params): array
    {
        $fax = $this->history->fax;
        $eventService = Yii::$container->get(IEventService::class);

        return
            [
                ...$params,
                'body'           => $eventService->getBodyByEventType($this->history) .
                    ' - ' .
                    (isset($fax->document) ? Html::a(
                        \Yii::t('app', 'view document'),
                        $fax->document->getViewUrl(),
                        [
                            'target'    => '_blank',
                            'data-pjax' => 0
                        ]
                    ) : ''),
                'footer'         => \Yii::t('app', '{type} was sent to {group}', [
                    'type'  => $fax ? $fax->getTypeText() : 'Fax',
                    'group' => isset($fax->creditorGroup) ? Html::a($fax->creditorGroup->name, ['creditors/groups'], ['data-pjax' => 0]) : ''
                ]),
                'footerDatetime' => $this->history->ins_ts,
                'iconClass'      => 'fa-fax bg-green'
            ];
    }
}