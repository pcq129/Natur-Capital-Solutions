<?php

namespace App\Services;

use App\Http\Requests\Banner\DeleteBannerRequest;
// use App\Http\Requests\Banner\CreateBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Models\Banner;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Log;
use App\Services\FileService;
use App\Constants\AppConstants;
use App\Enums\Status;
use App\Services\DTO\ServiceResponse;
use App\Enums\ServiceResponseType;

class BannerService
{

    public function __construct(protected FileService $imageUploadService) {}

    public function createBanner($newBannerData): ServiceResponse
    {


        // formatting and preparing data for db storage
        $imageLocation = $this->imageUploadService->uploadImage($newBannerData['image'], AppConstants::BANNER_STORAGE_FOLDER);

        $buttons = $this->formatButtons($newBannerData);
        $links = $this->formatLinks($newBannerData);

        Banner::create([
            'name' => $newBannerData['name'],
            'image' => $imageLocation,
            'banner_link' => $newBannerData['banner_link'],
            'overlay_heading' => $newBannerData['overlay_heading'],
            'overlay_text' => $newBannerData['overlay_text'],
            'priority' => $newBannerData['priority'],
            'buttons' => json_encode($buttons),
            'links'  => json_encode($links),
            'status' => Status::Active,
        ]);
        return ServiceResponse::success('Banner added successfully');
    }

    public function updateBanner(UpdateBannerRequest $request, $id): ServiceResponse
    {
        $bannerData = $request->validated();
        $banner = Banner::find($id);
        // dd($bannerData);
        if (!$banner) {
            return new ServiceResponse(ServiceResponseType::Error, 'Banner not found');
        }

        // Only update image if is changed
        if ($request->hasFile('image')) {
            $oldImage = $banner->image;
            $banner->fill([
                'image' => $this->imageUploadService->uploadImage($request->image, AppConstants::BANNER_STORAGE_FOLDER),
            ]);
            // Attempt to delete old image
            $this->imageUploadService->deleteImage($oldImage);
        }

        $buttons = json_encode($this->formatButtons($bannerData));
        $links = json_encode($this->formatLinks($bannerData));

        $banner->fill([
            'name' => $bannerData['name'],
            'banner_link' => $bannerData['banner_link'],
            'overlay_heading' => $bannerData['overlay_heading'],
            'overlay_text' => $bannerData['overlay_text'],
            'priority' => $bannerData['priority'],
            'status' => $bannerData['status'] ? Status::Active : Status::Inactive,
            'buttons' => $buttons,
            'links' => $links,
        ]);

        if ($banner->isDirty()) {
            $banner->save();
            return new ServiceResponse(ServiceResponseType::Success, 'Banner updated successfully');
        } else {
            return ServiceResponse::info('No changes detected');
        }
    }


    public function deleteBanner(int $id): ServiceResponse
    {

        $banner = Banner::find($id);

        if (!$banner) {
            return ServiceResponse::Error('Banner not found');
        }

        // Additionaly, delete image
        $this->imageUploadService->deleteImage($banner->image);
        $banner->delete();
        return ServiceResponse::Success('Banner deleted successfully');
    }

    public function fetchBanners($pageLength = null): ServiceResponse
    {
        $bannerData = Banner::orderBy('id', 'desc')->paginate($pagelength ?? 5);
        return ServiceResponse::Success('Banners fetched successfully', $bannerData);
    }

    private function formatButtons($userInputs): array
    {
        $buttons = array_filter([
            'button_one' => array_filter([
                'text' => $userInputs['banner_button_one_text'] ?? null,
                'link' => $userInputs['banner_button_one_action'] ?? null
            ]),
            'button_two' => array_filter([
                'text' => $userInputs['banner_button_two_text'] ?? null,
                'link' => $userInputs['banner_button_two_action'] ?? null
            ])
        ]);

        return $buttons;
    }

    private function formatLinks($userInputs): array
    {
        $links = array_filter([
            'link_one' => array_filter([
                'text' => $userInputs['banner_link_one_text'] ?? null,
                'link' => $userInputs['banner_link_one_action'] ?? null,
            ]),
            'link_two' => array_filter([
                'text' => $userInputs['banner_link_two_text'] ?? null,
                'link' => $userInputs['banner_link_two_action'] ?? null,
            ])
        ]);

        return $links;
    }

    public function getSingleBanner(int $id)
    {
        $bannerData = Banner::find($id);
        return $bannerData;
    }
}
