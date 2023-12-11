<?php

namespace app\components\export;

use app\components\export\strategy\CsvStrategy;
use app\components\export\strategy\IExportStrategy;

class ExportStrategyFactory implements IExportStrategyFactory
{
    public function getStrategy(string $type): IExportStrategy
    {
        return match ($type) {
            ExportTypes::FORMAT_CSV => new CsvStrategy(),

            default => throw new \InvalidArgumentException("Unknown export type: {$type}"),
        };
    }
}