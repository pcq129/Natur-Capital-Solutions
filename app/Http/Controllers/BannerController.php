<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\DeleteBannerRequest;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Services\BannerService;
use Illuminate\Support\Facades\Log;
use App\Enums\ServiceResponseType;
use App\Services\ToasterService;
use PhpParser\Node\Stmt\TryCatch;

// TODO : IMPLEMENT ERROR HANDLING IN CONTROLLER METHOD
// DO NOT : implement try catch in services, only let the controller methods handle the exceptions/errors.


class BannerController extends Controller
{
    public function __construct(protected BannerService $bannerService, protected ToasterService $toasterService) {}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $action = $this->bannerService->getAllBanner();
            return view('pages.banner.index', ['data' => $action->data]);
        } catch (\Exception $e) {
            Log::error('Banner list fetching failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->toasterService->error('Error while fetching Banners');
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
    public function store(StoreBannerRequest $request)
    {
        try {
            $action = $this->bannerService->createBanner($request->validated());
            $this->toasterService->toast($action);
            return redirect()->route('banners.index');
        } catch (\Exception $e) {
            Log::error('Banner creation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->toasterService->error('Error while adding banner');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $banner = $this->bannerService->getSingleBanner((int) $id);
            // decode json data for better operability at frontend.
            $banner->buttons = json_decode($banner->buttons);
            $banner->links = json_decode($banner->links);
            return view('Pages.Banner.update', ['banner' => $banner]);
        } catch (\Exception $e) {
            Log::error('Fetching single banner failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->toasterService->error('Error fetching Banner data');
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
    public function destroy(string $id)
    {
        try{
            $action = $this->bannerService->deleteBanner((int) $id);
        $this->toasterService->toast($action);
        return redirect()->back();
        }catch(\Exception $e){
            Log::error('Banner delete failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->toasterService->error('Error while deleting  banner');
            return redirect()->back();
        }
    }
}
