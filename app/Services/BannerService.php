<?php

namespace App\Services;

use App\Http\Requests\Banner\UpdateBannerRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Banner;
use App\Services\FileService;
use App\Constants\AppConstants;
use App\Constants\BannerConstants as CONSTANTS;
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
            $saveImage = $this->imageUploadService->saveFile($newBannerData['image'], CONSTANTS::BANNER_STORAGE_FOLDER);
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
                return ServiceResponse::success(CONSTANTS::STORE_SUCCESS);
            } else {
                return ServiceResopnse::error(CONSTANTS::STORE_FAIL);
            }
        } catch (\Throwable $e) {
            $message = CONSTANTS::STORE_FAIL;
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }

    public function updateBanner(UpdateBannerRequest $request, $id): ServiceResponse
    {
        try {
            $bannerData = $request->validated();
            $banner = Banner::findOrFail($id);
            if ($request->hasFile('image')) {
                $oldImage = $banner->image;
                $saveImage = $this->imageUploadService->saveFile($request->image, CONSTANTS::BANNER_STORAGE_FOLDER);
                $imageLocation = $saveImage->data;
                $banner->fill([
                    'image' => $imageLocation,
                ]);
                $this->imageUploadService->deleteFile($oldImage);
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
                return new ServiceResponse(ServiceResponseType::SUCCESS, CONSTANTS::UPDATE_SUCCESS);
            } else {
                return ServiceResponse::info(CONSTANTS::NO_CHANGE);
            }
        } catch (\Exception $e) {
            $message = CONSTANTS::UPDATE_FAIL;
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }


    public function deleteBanner(int $id): ServiceResponse
    {
        try {
            $banner = Banner::findOrFail($id);

            if (!$banner) {
                return ServiceResponse::Error(CONSTANTS::NOT_FOUND);
            }

            // Additionaly, delete image
            $this->imageUploadService->deleteFile($banner->image);
            $banner->delete();
            return ServiceResponse::Success(CONSTANTS::DELETE_SUCCESS);
        } catch (\Exception $e) {
            $message = CONSTANTS::DELETE_FAIL;
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }

        public function fetchBanners($request): ServiceResponse
        {
            try {
                if ($request->ajax()) {
                    $query = Banner::query()->orderBy('id', 'DESC');

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

                    return ServiceResponse::success(CONSTANTS::FETCH_SUCCESS, $bannerData);
                } else {
                    // non ajax request are handled by controller directly and return banner index view.
                    // thus no logic to be included here. Checks just for safety purposes.
                    return ServiceResponse::error(AppConstants::NON_AJAX_REQUEST);
                }
            } catch (\Exception $e) {
                $message = CONSTANTS::FETCH_FAIL;
                Handler::logError($e, $message);
                return ServiceResponse::error($message);
            }
        }

    public function getSingleBanner(int $id): ServiceResponse
    {
        try {
            $bannerData = Banner::findOrFail($id);

            if ($bannerData) {
                return ServiceResponse::success(CONSTANTS::FETCH_SUCCESS, $bannerData);
            } else {
                return ServiceResponse::error(CONSTANTS::NOT_FOUND);
            }
        } catch (\Throwable $e) {
            $message = CONSTANTS::FETCH_FAIL;
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
