<?php

namespace app\components\export;

use app\components\export\strategy\IExportStrategy;

interface IExportStrategyFactory
{
    public function getStrategy(string $type): IExportStrategy;
}