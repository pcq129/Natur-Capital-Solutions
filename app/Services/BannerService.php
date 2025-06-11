<?php

namespace App\Services;

use App\Http\Requests\Banner\UpdateBannerRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Banner;
use App\Services\FileService;
use App\Constants\AppConstants;
use App\Enums\Status;
use App\Services\DTO\ServiceResponse;
use App\Enums\ServiceResponseType;
use App\Exceptions\Handler;
use Exception;
// use App\Http\Requests\Banner\CreateBannerRequest;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Log;

class BannerService
{

    public function __construct(protected FileService $imageUploadService) {}

    public function createBanner($newBannerData): ServiceResponse
    {
        try {
            // formatting and preparing data for db storage
            $saveImage = $this->imageUploadService->uploadImage($newBannerData['image'], AppConstants::BANNER_STORAGE_FOLDER);
            $imageLocation = $saveImage->data;
            if ($imageLocation) {
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
                    'status' => Status::ACTIVE,
                ]);
                return ServiceResponse::success('Banner added successfully');
            } else {
                return ServiceResopnse::error('Error while storing image');
            }
        } catch (\Throwable $e) {
            $message = 'Error while creating Banner';
            Handler->logError($e, $message);
            return ServiceResponse::error($message);
        }
    }

    public function updateBanner(UpdateBannerRequest $request, $id): ServiceResponse
    {
        try {
            $bannerData = $request->validated();
            $banner = Banner::findOrFail($id);
            // dd($bannerData);
            if (!$banner) {
                return new ServiceResponse(ServiceResponseType::ERROR, 'Banner not found');
            }

            // Only update image if is changed
            if ($request->hasFile('image')) {
                $oldImage = $banner->image;
                $saveImage = $this->imageUploadService->uploadImage($request->image, AppConstants::BANNER_STORAGE_FOLDER);
                $imageLocation = $saveImage->data;
                $banner->fill([
                    'image' => $imageLocation,
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
                'status' => $bannerData['status'] ? Status::ACTIVE : Status::INACTIVE,
                'buttons' => $buttons,
                'links' => $links,
            ]);

            if ($banner->isDirty()) {
                $banner->save();
                return new ServiceResponse(ServiceResponseType::SUCCESS, 'Banner updated successfully');
            } else {
                return ServiceResponse::info('No changes detected');
            }
        } catch (\Exception $e) {
            $message = 'Error while updating Banner';
            Handler->logError($e, $message);
            return ServiceResponse::error($message);
        }
    }


    public function deleteBanner(int $id): ServiceResponse
    {
        try {
            $banner = Banner::findOrFail($id);

            if (!$banner) {
                return ServiceResponse::Error('Banner not found');
            }

            // Additionaly, delete image
            $this->imageUploadService->deleteImage($banner->image);
            $banner->delete();
            return ServiceResponse::Success('Banner deleted successfully');
        } catch (\Exception $e) {
            $message = 'Error while deleting Banner';
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }

        public function fetchBanners($request): ServiceResponse
        {
            try {
                if ($request->ajax()) {
                    $query = Banner::query();

                    if ($request->filled('status')) {
                        $query->where('status', (int) $request->status);
                    }

                    $bannerData = DataTables::of($query)
                        ->addColumn('status', function ($row) {
                            return $row->status == Status::ACTIVE ? 'Active' : 'Inactive';
                        })
                        ->addColumn('image', function ($row) {
                            return '<img src="' . $row->image . '" height="50px">';
                        })
                        ->addColumn('actions', function ($row) {
                            $editUrl = route('banners.edit', $row->id);
                            $target = Banner::DELETE_MODAL_ID;
                            return view('Partials.actions', ['edit' => $editUrl,  'row' => $row, 'target' => $target]);
                        })
                        ->rawColumns(['image', 'actions'])
                        ->make(true);

                    return ServiceResponse::success('Banners fetched successfully', $bannerData);
                } else {
                    // non ajax request are handled by controller directly and return banner index view.
                    // thus no logic to be included here. Checks just for safety purposes.
                    return ServiceResponse::error('Non ajax request');
                }
            } catch (\Exception $e) {
                $message = 'Error while fetching Banners';
                Handler::logError($e, $message);
                return ServiceResponse::error($message);
            }
        }

    public function getSingleBanner(int $id): ServiceResponse
    {
        try {
            $bannerData = Banner::findOrFail($id);

            if ($bannerData) {
                return ServiceResponse::success('Banner fetched successfully', $bannerData);
            } else {
                return ServiceResponse::error('Banner not found');
            }
        } catch (\Throwable $e) {
            $message = 'Error while fetching Banner details';
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }









    /*
    The following function are for small scope changes and
    do not require error handling (as of 4/6/25). Thus for
    now no error handlind is implemented in the following
    methods.

    Also exception in these methods might bubble up in the
    host method that calls it. Thus technically beingh ha-
    ndled automatically.
    */

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
}
