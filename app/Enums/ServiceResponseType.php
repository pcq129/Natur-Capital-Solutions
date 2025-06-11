<?php

namespace App\Enums;

enum ServiceResponseType: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case INFORMATION = 'info';
}
