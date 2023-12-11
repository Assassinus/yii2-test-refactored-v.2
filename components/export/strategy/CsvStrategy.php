<?php

namespace app\components\export\strategy;

use app\services\history\event\IEventService;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\View;

class CsvStrategy implements IExportStrategy
{
    public const FILE_NAME = 'history';

    /**
     * @param mixed $historySearchData
     * @return string
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function execute(mixed $historySearchData): string
    {
        return $this->generateCsv($historySearchData);
    }

    /**
     * @param array $data
     * @return string|null
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function generateCsv(array $data): ?string
    {
        $eventService = \Yii::$container->get(IEventService::class);
        $handle = fopen('php://temp', 'r+');

        $columnHeaders = ["Date", "User", "Type", "Event", "Message"];
        fputcsv($handle, $columnHeaders, ',', '"');

        foreach ($data as &$raw) {
            foreach ($raw as $key => &$item) {
                if ($key === 'event') {
                    $raw['message'] = $eventService->getText($item) . ': ' . $raw['message'];
                }
            }
            fputcsv($handle, $raw, ',', '"');
        }

        rewind($handle);
        $contents = null;

        while (!feof($handle)) {
            $contents .= fread($handle, 8192);
        }

        fclose($handle);

        return $contents;
    }
}