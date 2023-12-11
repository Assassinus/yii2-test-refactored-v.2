<?php

namespace app\services\history\event\groups;

enum Call: string
{
    case EVENT_INCOMING_CALL = 'incoming_call';
    case EVENT_OUTGOING_CALL = 'outgoing_call';
}