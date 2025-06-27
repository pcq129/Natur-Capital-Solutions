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
    public function saveFile(UploadedFile $file, string $folder): ServiceResponse
    {
        try {
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload.');
            }
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Store the file in the given folder under the 'public' disk
            $file->storeAs($folder, $fileName, 'public');

            // Return the public path for frontend use
            $fileLocation = 'storage/' . $folder . '/' . $fileName;
            return ServiceResponse::success('File stored successfully', $fileLocation);
        } catch (\Exception $e) {
            $message = 'Uncaght exception while storing File';
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }


    public function deleteFile(string $fileLocation, string $disk = 'public'): ServiceResponse
    {
        try {
            if (Storage::disk($disk)->exists($fileLocation) && Storage::disk($disk)->delete($fileLocation)) {
                return ServiceResponse::success('File deleted successfully');
            } else {
                return ServiceResponse::error('File not found');
            }
        } catch (\Exception $e) {
            $message = "Uncaught exception while deleting File";
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }

}
