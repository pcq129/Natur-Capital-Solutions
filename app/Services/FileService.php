<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileService
{
    public function uploadImage(UploadedFile $image, string $folder): string
    {
        if (!$image->isValid()) {
            throw new \Exception('Invalid image upload.');
        }
        $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

        // Store the image in the given folder under the 'public' disk
        $image->storeAs($folder, $imageName, 'public');

        // Return the public path for frontend use
        return 'storage/' . $folder . '/' . $imageName;
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

}
