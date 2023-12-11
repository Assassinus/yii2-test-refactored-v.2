<?php

namespace app\components\history;

use app\components\history\views\CallItemView;
use app\components\history\views\ChangeQualityItemView;
use app\components\history\views\ChangeTypeItemView;
use app\components\history\views\DefaultItemView;
use app\components\history\views\FaxItemView;
use app\components\history\views\SmsItemView;
use app\components\history\views\TaskItemView;
use app\models\History;
use app\services\history\event\groups\Call;
use app\services\history\event\groups\Customer;
use app\services\history\event\groups\Fax;
use app\services\history\event\groups\Sms;
use app\services\history\event\groups\Task;


class EventViewFactory implements IEventViewFactory
{
    /**
     * @param History $history
     */
    public function __construct(private readonly History $history)
    {
    }

    /**
     * @param $eventType
     * @return IEventItemView
     */
    public function createView($eventType): IEventItemView
    {
        return match (true) {
            in_array($eventType, array_map(fn($case) => $case->value, Task::cases())) => new TaskItemView($this->history),
            in_array($eventType, array_map(fn($case) => $case->value, Sms::cases())) => new SmsItemView($this->history),
            in_array($eventType, array_map(fn($case) => $case->value, Fax::cases())) => new FaxItemView($this->history),
            in_array($eventType, array_map(fn($case) => $case->value, Call::cases())) => new CallItemView($this->history),

            $eventType === Customer::EVENT_CUSTOMER_CHANGE_TYPE => new ChangeTypeItemView($this->history),
            $eventType === Customer::EVENT_CUSTOMER_CHANGE_QUALITY => new ChangeQualityItemView($this->history),

            default => new DefaultItemView($this->history)
        };
    }
}