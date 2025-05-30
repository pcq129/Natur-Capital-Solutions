<?php

namespace App\Enums;

enum ServiceResponseType: string
{
    case Success = 'success';
    case Error = 'error';
    case Information = 'info';
}
