<?php

namespace App\Services;

use App\Models\Service;
use App\Constants\ServiceConstants;
use App\Enums\FileType;
use App\Enums\Status;
use App\Models\ServiceSection;
use App\Services\DTO\ServiceResponse;

class ServiceService
{

    public function __construct(private FileService $fileService) {}

    public function StoreService(array $data)
    {
        // dd($data);
        $image =  $data['serviceIcon'];
        $serviceIconLocation = $this->fileService->saveFile($image, ServiceConstants::SERVICEICONS_FOLDER);
        $service = Service::create(
            [
                'name' => $data['serviceName'],
                'description' => $data['serviceDescription'],
                'icon' => $serviceIconLocation->data,
                'status' => Status::ACTIVE,
            ]
        );

        $id = $service->id;

        $count = 1;
        foreach ($data['sectionName'] as $key => $value) {
            $serviceSection = ServiceSection::create([
                'service_id' => $id,
                'content' => $data['servicesection-trixFields'][$key],
                'heading' => $data['sectionName'][$key],
                'priority' => $count
            ]);


            foreach (json_decode($data['attachment-servicesection-trixFields'][$key]) as $attachment) {
                $serviceSection->resources()->create([
                    'resource_type' => FileType::TRIX_ATTACHMENTS,
                    'resourceable_id' => $serviceSection->id,
                    'resource' => $attachment,
                    'priority' => $count
                ]);
            }
            $count++;
        }
        return ServiceResponse::success(ServiceConstants::STORE_SUCCESS);
    }

    public function UpdateService(array $data, Service $service)
    {
        $deletedFiles = json_decode($data['deletedFiles'] ?? '[]');
        $deletedSections = json_decode($data['removedSections'] ?? '[]');

        dd($data, $deletedFiles, $deletedSections);

        $service->fill(
            [
                'name' => $data['serviceName'],
                'description' => $data['serviceDescription'],
                'status' => $data['status'] ?? 0
            ]
        );

        foreach($deletedFiles as $image){
            $this->fileService->deleteFile($image, 'public');
        }


        if (isset($data['serviceIcon'])) {
            $image =  $data['serviceIcon'];
            $serviceIconLocation = $this->fileService->saveFile($image, ServiceConstants::SERVICEICONS_FOLDER);
            $service->fill(
                [
                    'icon' => $serviceIconLocation->data,
                ]
            );
        }




        if ($service->isDirty()) {
            $service->save();
            return ServiceResponse::success(ServiceConstants::UPDATE_SUCCESS);
        } else {
            return ServiceResponse::info('No changes detected');
        }
    }

    public function DeleteService(Service $service)
    {
        $sections = $service->serviceSections()->get();

        foreach ($sections as $section) {
            $resources = $section->resources()->get('resource');
            foreach ($resources as $resource) {
                $delete = $this->fileService->deleteFile($resource->resource);
                if ($delete->status === false) {
                    logger()->error('Failed to delete resource file: ' . $resource->resource);
                } else {
                    $resource->delete();
                }
            }
            $section->delete();
        }

        $service->delete();

        return ServiceResponse::success(ServiceConstants::DELETE_SUCCESS);
    }
}
