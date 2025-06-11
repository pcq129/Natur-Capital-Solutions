<?php

namespace App\Services\DTO;

use App\Enums\ServiceResponseType;

class ServiceResponse
{
    public function __construct(
        public ServiceResponseType $status,
        public string $message,
        public mixed $data = null,
    ) {}

    public static function success(string $message, mixed $data = null): self
    {
        return new self(ServiceResponseType::SUCCESS, $message, $data);
    }

    public static function error(string $message, mixed $data = null): self
    {
        return new self(ServiceResponseType::ERROR, $message, $data);
    }

    public static function info(string $message, mixed $data = null): self
    {
        return new self(ServiceResponseType::INFORMATION, $message, $data);
    }
}
