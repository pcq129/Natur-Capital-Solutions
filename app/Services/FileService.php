<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Exceptions\Handler;
use App\Services\DTO\ServiceResponse;
use Exception;

class FileService
{
    public function uploadImage(UploadedFile $image, string $folder): ServiceResponse
    {
        try {
            if (!$image->isValid()) {
                throw new \Exception('Invalid image upload.');
            }
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Store the image in the given folder under the 'public' disk
            $image->storeAs($folder, $imageName, 'public');

            // Return the public path for frontend use
            $imageLocation = 'storage/' . $folder . '/' . $imageName;
            return ServiceResponse::success('Image stored successfully', $imageLocation);
        } catch (\Exception $e) {
            $message = 'Uncaght exception while storing Image';
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }


    public function deleteImage(string $imageLocation, string $disk = 'public'): ServiceResponse
    {
        try {
            if (Storage::disk($disk)->exists($imageLocation) && Storage::disk($disk)->delete($imageLocation)) {
                return ServiceResponse::success('Image deleted successfully');
            } else {
                return ServiceResponse::error('Image not found');
            }
        } catch (\Exception $e) {
            $message = "Uncaught exception while deleting Image";
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }

}
