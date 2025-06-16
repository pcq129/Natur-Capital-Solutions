<?php

namespace App\Traits;

use App\Exceptions\Handler;


trait ExceptionHandler
{
    private function Exception(\Exception $e, $message)
    {
        $this->toasterService->exceptionToast($message);
        Handler::logError($e, $message);
    }
}
