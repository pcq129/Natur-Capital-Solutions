<?php

namespace App\Services;

use App\Models\Service;
use App\Constants\ServiceConstants;
use App\Enums\Status;
use App\Models\ServiceSection;
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
                'name' => $data['serviceName'],
                'description' => $data['serviceDescription'],
                'icon' => $serviceIconLocation->data,
                'status' => Status::ACTIVE
            ]
        );

        $id = $service->id;
        foreach ($data['sectionName'] as $key => $value) {
            ServiceSection::create([
                'service_id' => $id,
                'content' => $data['servicesection-trixFields'][$key],
                'heading' => $data['sectionName'][$key],
                'priority' => $key+1,
                'attachment-servicesection-trixFields' => "attachment-servicesection-trixFields"
            ]);
        }
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
