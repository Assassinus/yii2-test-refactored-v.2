<?php

namespace app\services\history\event\groups;

enum Sms: string
{
    case EVENT_INCOMING_SMS = 'incoming_sms';
    case EVENT_OUTGOING_SMS = 'outgoing_sms';
}