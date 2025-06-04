<?php

namespace App\Exceptions;

use App\Services\DTO\ServiceResponse;
use App\Enums\ServiceResponseType;
use Exception;


class Handler
{
    public static function logError(Exception $e, string $message){
        \Log::error(`$message`.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
    }
}
