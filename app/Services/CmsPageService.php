<?php

namespace App\Services;

use App\Http\Requests\CmsPage\CreateCmsPageRequest;
use App\Http\Requests\CmsPage\UpdateCmsPageRequest;
use App\Models\CmsPage;
use App\Enums\Status;
use App\Exceptions\Handler;
use App\Services\DTO\ServiceResponse;

class CmsPageService
{
    public function createCmsPage(CreateCmsPageRequest $request): ServiceResponse
    {

        $successMessage = "CMS Page created successfully";
        $failureMessage = "Error while creating CMS page";


        try {
            $cmsPage = $request->only(
                'name',
                'language',
                'cmspage-trixFields'
            );

            CmsPage::create([
                'name' => $cmsPage['name'],
                'language' => $cmsPage['language'],
                'content'=> $cmsPage['cmspage-trixFields']['cmsText'],
                'cmspage-trixFields' =>  $cmsPage['cmspage-trixFields'],
                'status' => Status::Active
            ]);

            return ServiceResponse::success($successMessage);
        } catch (\Throwable $e) {
            Handler::logError($e, $failureMessage);
            return ServiceResponse::error($failureMessage);
        }
    }

    public function updateCmsPage(UpdateCmsPageRequest $request) {}

    public function fetchCmsPage($id) {
        $cmsPage = CmsPage::find($id);
        return ServiceResponse::success('success', $cmsPage);
    }

    public function deleteCmsPage($id) {}
}
