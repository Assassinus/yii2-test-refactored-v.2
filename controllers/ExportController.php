<?php

namespace app\controllers;

use app\components\export\ExportTypes;
use app\components\export\IExportStrategyFactory;
use app\components\export\strategy\CsvStrategy;
use app\models\search\HistorySearch;
use app\models\search\IHistorySearch;
use yii\web\Controller;

class ExportController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly IHistorySearch $historySearch,
        private readonly IExportStrategyFactory $strategyFactory,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * @param string $exportType
     * @return mixed
     */
    public function actionIndex(string $exportType): mixed
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . CsvStrategy::FILE_NAME . '_' . time() . '.' . ExportTypes::FORMAT_CSV . '"');

        $historySearchData = $this->historySearch->vanillaSqlSearch(HistorySearch::OPTIMAL_BATCH_SIZE);
        $csvExportStrategy = $this->strategyFactory->getStrategy($exportType);

        return $csvExportStrategy->execute($historySearchData);
    }
}