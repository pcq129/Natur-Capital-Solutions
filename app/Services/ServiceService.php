<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Resource;
use App\Models\ServiceSection;
use App\Constants\ServiceConstants;
use App\Enums\FileType;
use App\Enums\Status;
use App\Services\DTO\ServiceResponse;
use Te7aHoudini\LaravelTrix\Http\Controllers\TrixAttachmentController;

class ServiceService
{

    public function __construct(private FileService $fileService, private TrixAttachmentController $attachmentActions) {}

    public function StoreService(array $data)
    {
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
        $addedFiles = json_decode($data['addedFiles'] ?? '[]');

        $service->fill(
            [
                'name' => $data['serviceName'],
                'description' => $data['serviceDescription'],
                'status' => $data['status'] ?? 0
            ]
        );

        if (isset($data['currentServiceSection-trixFields'])) {
            $sectionCount = 1;
            foreach ($data['currentServiceSection-trixFields'] as $key => $value) {
                $section = $service->serviceSections()->find($key);
                $section->fill(
                    [
                        'content' => $value,
                        'heading' => $data['currentServiceSectionName'][$key],
                        'priority' => $sectionCount
                    ]
                );

                $section->save();
                $sectionCount++;
            }
        }

        if (isset($data['servicesection-trixFields'])) {
            foreach ($data['servicesection-trixFields'] as $key => $value) {
                $service->serviceSections()->create(
                    [
                        'content' => $value,
                        'heading' => $data['sectionName'][$key],
                        'priority' => $sectionCount,
                    ]
                );
                $sectionCount++;
            }
        }


        foreach ($addedFiles as $attachment) {
            $fileName = str_replace(config('app.url') . '/storage/', '', $attachment->fileUrl);
            $sectionId = $attachment->id;

            Resource::create([
                'resource_type' => FileType::TRIX_ATTACHMENTS,
                'resourceable_id' => $sectionId,
                'resourceable_type' => ServiceSection::class,
                'resource' => $fileName,
                'priority' => 1
            ]);
        }


        foreach ($deletedFiles as $attachment) {
            $fileName = str_replace(config('app.url') . '/storage/', '', $attachment->fileUrl);
            $sectionId = $attachment->id;
            if ($sectionId != 0) {
                $deleteAction = $this->fileService->deleteFile($fileName, 'public');

                if ($deleteAction->status == true) {
                    ServiceSection::find($sectionId)->resources()->where('resource', $fileName)->delete();
                } else {
                    logger()->error('Failed to delete resource file: ' . $fileName);
                }
            } else {
                $deleteAction = $this->fileService->deleteFile($fileName, 'public');
                if ($deleteAction->status == false) {
                    logger()->error('Failed to delete resource file: ' . $fileName);
                } else {
                    Resource::where('resource', $fileName)->delete();
                }
            }
        }

        foreach ($deletedSections as $sectionId) {
            $section = ServiceSection::find($sectionId);
            $resources = $section->resources()->get();
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


        if (isset($data['serviceIcon'])) {
            $image =  $data['serviceIcon'];
            $serviceIconLocation = $this->fileService->saveFile($image, ServiceConstants::SERVICEICONS_FOLDER);
            $service->fill(
                [
                    'icon' => $serviceIconLocation->data,
                ]
            );
        }


        $service->save();
        return ServiceResponse::success(ServiceConstants::UPDATE_SUCCESS);
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
