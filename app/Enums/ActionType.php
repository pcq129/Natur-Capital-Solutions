<?php

namespace App\Enums;

enum ActionType: string
{
    case TELEPHONE ='tel';
    case MAIL = 'mailto';
    case NONE = 'none';
    case URL = 'url';
}
