<?php

namespace app\services\history\event;

use app\models\Customer;
use app\models\History;
use app\services\history\detail\IDetailService;
use app\services\history\event\groups\Call;
use app\services\history\event\groups\Customer as CustomerEventGroup;
use app\services\history\event\groups\Fax;
use app\services\history\event\groups\Sms;
use app\services\history\event\groups\Task;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class EventService implements IEventService
{
    /**
     * @param $event
     * @return string|null
     */
    public function getText($event): ?string
    {
        return static::getTexts()[$event] ?? $event;
    }

    /**
     * @return array
     */
    public function getTexts(): array
    {
        return [
            Task::EVENT_CREATED_TASK->value   => Yii::t('app', 'Task created'),
            Task::EVENT_UPDATED_TASK->value   => Yii::t('app', 'Task updated'),
            Task::EVENT_COMPLETED_TASK->value => Yii::t('app', 'Task completed'),

            Sms::EVENT_INCOMING_SMS->value => Yii::t('app', 'Incoming message'),
            Sms::EVENT_OUTGOING_SMS->value => Yii::t('app', 'Outgoing message'),

            Call::EVENT_OUTGOING_CALL->value => Yii::t('app', 'Outgoing call'),
            Call::EVENT_INCOMING_CALL->value => Yii::t('app', 'Incoming call'),

            CustomerEventGroup::EVENT_CUSTOMER_CHANGE_TYPE->value    => Yii::t('app', 'Type changed'),
            CustomerEventGroup::EVENT_CUSTOMER_CHANGE_QUALITY->value => Yii::t('app', 'Property changed'),

            Fax::EVENT_INCOMING_FAX->value => Yii::t('app', 'Incoming fax'),
            Fax::EVENT_OUTGOING_FAX->value => Yii::t('app', 'Outgoing fax'),
        ];
    }

    /**
     * @param History $history
     * @return string|null
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function getBodyByEventType(History $history): ?string
    {
        $detailService = Yii::$container->get(IDetailService::class);

        return match (true) {
            in_array($history->event, array_map(fn($case) => $case->value, Task::cases())) => sprintf('%s: %s', $this->getText($history->event), $history->task->title ?? ''),
            in_array($history->event, array_map(fn($case) => $case->value, Sms::cases())) => $history?->sms?->message ? $history->sms->message : '',

            $history->event === CustomerEventGroup::EVENT_CUSTOMER_CHANGE_TYPE->value =>
                $this->getText($history->event) . ' ' .
                (Customer::getTypeTextByType($detailService->getOldValue($history->detail, 'type')) ?? "not set") . ' to ' .
                (Customer::getTypeTextByType($detailService->getNewValue($history->detail, 'type')) ?? "not set"),

            $history->event === CustomerEventGroup::EVENT_CUSTOMER_CHANGE_QUALITY->value =>
                $this->getText($history->event) . ' ' .
                (Customer::getQualityTextByQuality($detailService->getOldValue($history->detail, 'quality')) ?? "not set") . ' to ' .
                (Customer::getQualityTextByQuality($detailService->getNewValue($history->detail, 'quality')) ?? "not set"),

            in_array($history->event, array_map(fn($case) => $case->value, Call::cases())) =>
            ($call = $history->call) ?
                $call->totalStatusText . ($call->getTotalDisposition(false) ? " <span class='text-grey'>" . $call->getTotalDisposition(false) . "</span>" : "")
                : '<i>Deleted</i> ',

            default => $this->getText($history->event),
        };
    }
}