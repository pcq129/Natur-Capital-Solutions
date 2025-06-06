<?php

namespace App\Exceptions;

use Throwable;

class Handler
{
    public static function logError(Throwable $e, string $message){
        \Log::error(`$message`.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
    }
}
