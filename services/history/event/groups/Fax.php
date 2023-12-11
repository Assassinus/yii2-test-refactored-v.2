<?php

namespace app\services\history\event\groups;

enum Fax: string
{
    case EVENT_INCOMING_FAX = 'incoming_fax';
    case EVENT_OUTGOING_FAX = 'outgoing_fax';
}