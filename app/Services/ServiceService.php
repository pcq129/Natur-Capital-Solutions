<?php

namespace App\Services;

use App\Models\Service;
use App\Constants\ServiceConstants;
use App\Enums\Status;
use App\Services\DTO\ServiceResponse;

class ServiceService
{

    public function __construct(private FileService $fileService) {}

    public function StoreService(array $data)
    {

        $image =  $data['serviceIcon'];
        $serviceIconLocation = $this->fileService->saveFile($image, ServiceConstants::SERVICEICONS_FOLDER);
        $service = Service::create(
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'icon' => $serviceIconLocation->data,
                'status' => Status::ACTIVE
            ]
        );
        return ServiceResponse::success(ServiceConstants::STORE_SUCCESS);
    }

    public function UpdateService(array $data, Service $service)
    {

        if (isset($data['serviceIcon'])) {
            $image =  $data['serviceIcon'];
            $serviceIconLocation = $this->fileService->saveFile($image, ServiceConstants::SERVICEICONS_FOLDER);
            $service->fill(
                [
                    'icon' => $serviceIconLocation->data,
                ]
            );
        }
        $service->fill(
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'status' => $data['status'] ?? 0
            ]
        );



        if ($service->isDirty()) {
            $service->save();
            return ServiceResponse::success(ServiceConstants::UPDATE_SUCCESS);
        } else {
            return ServiceResponse::info('No changes detected');
        }
    }
}
