<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Banner\CreateBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Services\BannerService;
use Illuminate\Support\Facades\Log;
use App\Services\ToasterService;

class BannerController extends Controller
{
    public function __construct(protected BannerService $bannerService, protected ToasterService $toasterService) {}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $action = null;
            if ($request->ajax()) {
                $action = $this->bannerService->fetchBanners($request);
                return ($action->data);
            } else {
                return view('Pages.Banner.index');
            }
        } catch (\Exception $e) {
            Log::error('Banner list fetching failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->toasterService->exceptionToast('Error while fetching Banners');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Pages.Banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBannerRequest $request)
    {
        try {
            $action = $this->bannerService->createBanner($request->validated());
            $this->toasterService->toast($action);
            return redirect()->route('banners.index');
        } catch (\Exception $e) {
            Log::error('Banner creation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->toasterService->exceptionToast('Error while fetching Banners');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $action = $this->bannerService->getSingleBanner((int) $id);
            $banner = $action->data;
            // decode json data for better operability on frontend.
            if ($banner) {
                $banner->buttons = json_decode($banner->buttons);
                $banner->links = json_decode($banner->links);
                return view('Pages.Banner.update', ['banner' => $banner]);
            }
        } catch (\Exception $e) {
            Log::error('Fetching single banner failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            toastr()->error('Error fetching Banner data');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, $id)
    {
        try {
            $action = $this->bannerService->updateBanner($request, $id);
            $this->toasterService->toast($action);
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Banner edit failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->toasterService->error('Error while editing banner');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $action = $this->bannerService->deleteBanner((int) $id);
            $this->toasterService->toast($action);
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Banner delete failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->toasterService->error('Error while deleting  banner');
            return redirect()->back();
        }
    }
}
