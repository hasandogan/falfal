<?php

namespace App\Enums;

enum TarotProcessEnum: string
{
    case STARTED = 'started';
    case COMPLETED = 'completed';
    case WAITING = 'waiting';
    case IN_PROGRESS = 'in_progress';
    case FAILED = 'failed';
}
