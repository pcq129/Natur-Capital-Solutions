<?php

namespace App\Services;

use App\Enums\ServiceResponseType;
use App\Services\DTO\ServiceResponse;

class ToasterService
{
    public static function toast(ServiceResponse $response): void
    {
        if($response->status == ServiceResponseType::Success){
            toastr()->success($response->message);
        }
        else if($response->status == ServiceResponseType::Error){
            toastr()->error($response->message);
        }
        else if($response->status == ServiceResponseType::Information){
            toastr()->info($response->message);
        }
    }

    public static function errorToast(string $message):void
    {
        toastr()->error($message);
    }
}
