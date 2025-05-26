<?php

namespace App\Services;

use App\Http\Requests\Banner\DeleteBannerRequest;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BannerService
{

    public function createBanner(StoreBannerRequest $request): RedirectResponse
    {

        $bannerData = $request->validated();
        $banner = new Banner();
        $banner->image = $this->ImageUpload($request);
        $banner->buttons = $bannerData['buttons'] ?? null;
        $banner->links  = $bannerData['links'] ?? null;
        $banner->save();

        return redirect()->back()->with('success', 'Banner added successfully');
    }

    public function updateBanner(UpdateBannerRequest $request): RedirectResponse
    {

        $bannerData = $request->validated();
        $banner = Banner::find($bannerData['id']);
        if ($bannerData[image] != $banner->image) {
            $banner->image = $this->imageUpload($request);
        }
        $banner->buttons = $bannerData['buttons'] ?? null;
        $banner->links  = $bannerData['links'] ?? null;
        $banner->save();

        $deleteStatus = $this->deleteImage($bannerData[image]);

        if ($deleteStatus) {
            return redirect()->back()->with('success', 'Banner added successfully');
        }else{
            // not proper error handling. Throw more details so the user know what
            // exactly is happening. For now it thorows only uncaught exception.
            return redirect()->back()->with('Error', 'Uncaught Exception');
        }
    }


    public function imageUpload($request): string
    {

        $imageName = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->storeAs('images', $imageName, 'public');
        $image_path = 'storage/images/' . $imageName;

        return $image_path;
    }

    public function deleteImage(string $imageLocation, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($imageLocation)) {
            return Storage::disk($disk)->delete($imageLocation);
        }

        return false;
    }

    public function deleteBanner(DeleteBannerRequest $request): RedirectResponse
    {
        $bannerData = $request->validated();
        $banner = Banner::find($bannerData['id']);
        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully');
    }

    public function getAllBanner($request)
    {
        // not sending the images but only the paths.
        // handle accordingly in views. ¯\_(ツ)_/¯
        $bannerData = Banner::all();
        return $bannerData;
    }

    public function getSingleBanner($request)
    {

        $bannerData = $request->validated();
        $banner = Banner::find($bannerData['id']);
        return $banner;
    }
}
