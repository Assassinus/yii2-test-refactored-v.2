<?php

namespace app\services\history\event;

interface IEventService
{
    /**
     * @param $event
     * @return string|null
     */
    public function getText($event): ?string;

    /**
     * @return array
     */
    public function getTexts(): array;
}