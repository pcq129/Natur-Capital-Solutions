<?php

namespace App\Http\Controllers;

use App\Http\Requests\CmsPage\CreateCmsPageRequest;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use App\Services\CmsPageService;
use App\Services\ToasterService;
use App\Exceptions\Handler;


class CmsPageController extends Controller
{

    public function __construct(private CmsPageService $cmsPageService, private ToasterService $toasterService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Pages.CmsPage.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Pages.CmsPage.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCmsPageRequest $request)
    {
        try {
            $action = $this->cmsPageService->createCmsPage($request);
            $this->toasterService->toast($action);
            return redirect()->back();
        } catch (\Throwable $e) {
            $message = "Error while storing Cms Page";
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // dd('edit');
        // try {
            $action = $this->cmsPageService->fetchCmsPage($id);
            $cmsPage = $action->data;
            return view('Pages.CmsPage.update', ['data' => $cmsPage]);
        // } catch (\Throwable $e) {
        //     $message = "Error while fetching Cms Page data";
        //     Handler::logError($e, $message);
            // return redirect()->back();
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CmsPage $cmsPage)
    {
        dd($cmsPage, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsPage $cmsPage)
    {
        //
    }
}
