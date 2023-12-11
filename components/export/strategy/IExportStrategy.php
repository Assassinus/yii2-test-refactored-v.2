<?php

namespace app\components\export\strategy;

interface IExportStrategy
{
    /**
     * @param mixed $historySearchData
     * @return mixed
     */
    public function execute(mixed $historySearchData): mixed;
}