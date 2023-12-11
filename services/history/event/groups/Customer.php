<?php

namespace app\services\history\event\groups;

enum Customer: string
{
    case EVENT_CUSTOMER_CHANGE_TYPE = 'customer_change_type';
    case EVENT_CUSTOMER_CHANGE_QUALITY = 'customer_change_quality';
}