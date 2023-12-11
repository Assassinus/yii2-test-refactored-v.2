<?php

namespace app\components\history;

use yii\web\View;

class HistoryListRenderer
{
    /**
     * @param View $view
     */
    public function __construct(private readonly View $view)
    {
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params): string
    {
        return $this->view->render($view, $params);
    }
}