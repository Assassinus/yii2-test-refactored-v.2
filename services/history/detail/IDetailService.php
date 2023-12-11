<?php

namespace app\services\history\detail;

use stdClass;

interface IDetailService
{
    /**
     * @param $detail
     * @param $attribute
     */
    public function getChangedAttribute($detail, $attribute): ?stdClass;

    /**
     * @param $detail
     * @param $attribute
     * @return string|null
     */
    public function getOldValue($detail, $attribute): ?string;

    /**
     * @param $detail
     * @param $attribute
     * @return string|null
     */
    public function getNewValue($detail, $attribute): ?string;

    /**
     * @param $detail
     * @param $attribute
     * @return string|null
     */
    public function getData($detail, $attribute): ?string;
}