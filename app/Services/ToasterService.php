<?php

namespace App\Services;

use App\Enums\ServiceResponseType;
use App\Services\DTO\ServiceResponse;

class ToasterService
{
    public static function toast(ServiceResponse $response): void
    {
        if($response->status == ServiceResponseType::SUCCESS){
            toastr()->success($response->message);
        }
        else if($response->status == ServiceResponseType::ERROR){
            toastr()->error($response->message);
        }
        else if($response->status == ServiceResponseType::INFORMATION){
            toastr()->info($response->message);
        }
        else {
            toastr()->error($response->message);
        }
    }

    public static function exceptionToast(String $message): void{
        toastr()->error($message);
    }
}
