<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\DeleteBannerRequest;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Services\BannerService;
use Illuminate\Support\Facades\Log;

// TODO : IMPLEMENT ERROR HANDLING IN CONTROLLER METHOD
// DO NOT : implement try catch in services, only let the controller methods handle the exceptions/errors.


class BannerController extends Controller
{
    private BannerService $bannerService;


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Banner::orderBy('created_at', 'desc')->paginate(5);
        return view('pages.banner.index', ['data' => $data]);
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
            $this->bannerService->createBanner($request->validated());
            return redirect()->route('banners.index')->with('success', 'Banner added successfully');
        } catch (\Exception $e) {
            Log::error('Banner creation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            toastr()->error('Failed to add banner, Please try again');
            return redirect()->back();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::find($id);

        // decode json data for better operability at frontend.
        $banner->buttons = json_decode($banner->buttons);
        $banner->links = json_decode($banner->links);

        return view('Pages.Banner.update', ['banner' => $banner]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, $id)
    {
        $action = $this->bannerService->updateBanner($request, $id);

        if ($action['status'] == 'success') {
            toastr($action['message'], $action['status'], [], $action['status']);
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $action = $this->bannerService->deleteBanner($id);
        return redirect()->back()->with('success', 'Banner deleted successfully');
    }
}
