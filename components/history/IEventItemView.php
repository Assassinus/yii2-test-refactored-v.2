<?php

namespace app\components\history;

interface IEventItemView
{
    public function render($params): string;
}