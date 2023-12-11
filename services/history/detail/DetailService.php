<?php

namespace app\services\history\detail;

use stdClass;

class DetailService implements IDetailService
{
    /**
     * @param $detail
     * @param $attribute
     * @return object|null
     */
    public function getChangedAttribute($detail, $attribute): ?stdClass
    {
        $detail && $detail = json_decode($detail);

        return $detail->changedAttributes->{$attribute} ?? null;
    }

    /**
     * @param $detail
     * @param $attribute
     * @return string|null
     */
    public function getOldValue($detail, $attribute): ?string
    {
        $detail = $this->getChangedAttribute($detail, $attribute);

        return $detail?->old;
    }

    /**
     * @param $detail
     * @param $attribute
     * @return string|null
     */
    public function getNewValue($detail, $attribute): ?string
    {

        $detail = $this->getChangedAttribute($detail, $attribute);

        return $detail?->new;
    }

    /**
     * @param $detail
     * @param $attribute
     * @return string|null
     */
    public function getData($detail, $attribute): ?string
    {
        $detail = json_decode($detail);

        return $detail?->data?->{$attribute};
    }
}