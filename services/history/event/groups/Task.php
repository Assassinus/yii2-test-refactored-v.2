<?php

namespace app\services\history\event\groups;

enum Task: string
{
    case EVENT_CREATED_TASK = 'created_task';
    case EVENT_UPDATED_TASK = 'updated_task';
    case EVENT_COMPLETED_TASK = 'completed_task';
}