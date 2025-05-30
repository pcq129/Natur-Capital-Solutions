<?php

namespace App\Services;

use App\Http\Requests\Banner\DeleteBannerRequest;
// use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Models\Banner;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Log;
use App\Services\FileService;
use App\Constants\AppConstants;
use App\Enums\Status;

class BannerService
{



    public function createBanner($newBannerData) : array
    {


        // formatting and preparing data for db storage
        $imageUploadService = new FileService;
        $imageLocation = $imageUploadService->uploadImage($newBannerData['image'], AppConstants::BANNER_STORAGE_FOLDER);

        $buttons =$this->formatButtons($newBannerData);
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

        return ['status'=>'success','message'=>'Banner added successfully'];
    }

    private function formatButtons($userInputs){
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

    private function formatLinks($userInputs){
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


    public function updateBanner(UpdateBannerRequest $request, $id): array
    {

        $bannerData = $request->validated();
        $banner = Banner::find($id);
        // dd($bannerData);
        if (!$banner) {
            return ['status' => 'error', 'message' => 'Banner not found.'];
        }

        // Only update image if is changed
        $imageUploadService = new FileService;
        if ($request->hasFile('image')) {
            $oldImage = $banner->image;
            $banner->fill([
                'image' => $imageUploadService->uploadImage($request->image, AppConstants::BANNER_STORAGE_FOLDER),
            ]);
                // Attempt to delete old image
            $imageUploadService->deleteImage($oldImage);
        }


        $buttons = json_encode($this->formatButtons($bannerData));
        $links = json_encode($this->formatLinks($bannerData));

        // if($bannerData['status'] == 0){
        //     $bannerData['status']= Status::Inactive;
        // }else{
        //     $bannerData['status'] = Status::Active;
        // }
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
            return ['status' =>'success', 'message'=>'Banner updated successfully'];
        }

        return ['status' => 'info', 'message' => 'No changes detected.'];
    }

    // private function checkChange($a, $b):bool{
    //     if($a == $b){
    //         return true;
    //     }
    //     return false;
    // }


    // made a separate service for handling image uploads, not needed anymore

    // public function imageUpload($request): string
    // {
    //     try {
    //         $imageName = time() . '.' . $request->image->getClientOriginalExtension();
    //         $request->image->storeAs('images', $imageName, 'public');
    //         return 'storage/images/' . $imageName;
    //     } catch (\Throwable $e) {
    //         Log::error('Image upload failed: ' . $e->getMessage());
    //         throw new \Exception('Image upload failed.');
    //     }
    // }


    // private function deleteImage(string $imageLocation, string $disk = 'public'): bool
    // {
    //     try {
    //         if (Storage::disk($disk)->exists($imageLocation)) {
    //             return Storage::disk($disk)->delete($imageLocation);
    //         }
    //     } catch (\Throwable $e) {
    //         Log::error("Failed to delete image: $imageLocation - " . $e->getMessage());
    //     }

    //     return false;
    // }


    public function deleteBanner(number $request): array
    {
        $bannerData = $request->validated();
        $banner = Banner::find($bannerData['id']);

        if (!$banner) {
            return ['status'=>'error','message'=>'Banner not found.'];
        }

        // Additionaly, delete image
        $imageUploadService = new FileService;
        $imageUploadService->deleteImage($banner->image);
        $banner->delete();

        return ['status' =>'success', 'message'=> 'Banner deleted successfully'];
    }

    public function getAllBanner($request)
    {
        $bannerData = Banner::all();
        return $bannerData;
    }

    // not needed as no view implementation ¯\_(ツ)_/¯
    // public function getSingleBanner($request)
    // {
    //     $bannerData = $request->validated();
    //     $banner = Banner::find($bannerData['id']);
    //     return $banner;
    // }
}
