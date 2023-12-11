<?php

namespace app\components\history;

interface IEventViewFactory
{
    /**
     * @param $eventType
     * @return IEventItemView
     */
    public function createView($eventType): IEventItemView;
}