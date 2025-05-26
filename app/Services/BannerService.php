<?php

namespace App\Services;

use App\Http\Requests\Banner\DeleteBannerRequest;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BannerService
{

    use Illuminate\Support\Facades\Log;

    public function createBanner(StoreBannerRequest $request): RedirectResponse
    {
        try {
            $bannerData = $request->validated();
            $banner = new Banner();
            $banner->image = $this->imageUpload($request);
            $banner->buttons = $bannerData['buttons'] ?? null;
            $banner->links  = $bannerData['links'] ?? null;
            $banner->save();

            return redirect()->back()->with('success', 'Banner added successfully');
        } catch (\Throwable $e) {
            Log::error('Error creating banner: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Failed to add banner.');
        }
    }


    public function updateBanner(UpdateBannerRequest $request): RedirectResponse
    {
        try {
            $bannerData = $request->validated();
            $banner = Banner::find($bannerData['id']);

            if (!$banner) {
                return redirect()->back()->with('error', 'Banner not found.');
            }

            // Only update image if it's changed
            if ($request->hasFile('image')) {
                $oldImage = $banner->image;
                $banner->image = $this->imageUpload($request);

                // Attempt to delete old image
                $this->deleteImage(str_replace('storage/', '', $oldImage));
            }

            $banner->buttons = $bannerData['buttons'] ?? null;
            $banner->links  = $bannerData['links'] ?? null;
            $banner->save();

            return redirect()->back()->with('success', 'Banner updated successfully');
        } catch (\Throwable $e) {
            Log::error('Error updating banner: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Failed to update banner.');
        }
    }



    public function imageUpload($request): string
    {
        try {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('images', $imageName, 'public');
            return 'storage/images/' . $imageName;
        } catch (\Throwable $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            throw new \Exception('Image upload failed.');
        }
    }


    public function deleteImage(string $imageLocation, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($imageLocation)) {
                return Storage::disk($disk)->delete($imageLocation);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to delete image: $imageLocation - " . $e->getMessage());
        }

        return false;
    }


    public function deleteBanner(DeleteBannerRequest $request): RedirectResponse
    {
        try {
            $bannerData = $request->validated();
            $banner = Banner::find($bannerData['id']);

            if (!$banner) {
                return redirect()->back()->with('error', 'Banner not found.');
            }

            // Optionally delete image
            $this->deleteImage(str_replace('storage/', '', $banner->image));
            $banner->delete();

            return redirect()->back()->with('success', 'Banner deleted successfully');
        } catch (\Throwable $e) {
            Log::error('Error deleting banner: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Failed to delete banner.');
        }
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
