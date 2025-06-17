<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CmsPage\CreateCmsPageRequest;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use App\Services\CmsPageService;
use App\Services\ToasterService;
use App\Exceptions\Handler;
use App\Http\Requests\CmsPage\UpdateCmsPageRequest;
use App\Http\Controllers\Controller;

class CmsPageController extends Controller
{

    public function __construct(private CmsPageService $cmsPageService, private ToasterService $toasterService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $action = $this->cmsPageService->indexCmsPage($request);
                return ($action->data);
            } else {
                return view('Pages.CmsPage.index');
            }
        } catch (\Throwable $e) {
            $message = 'Error while fetching CMS Page list';
            Handler::logError($e, $message);
            $this->toasterService->exceptionToast($message);
            return redirect()->back();
        }
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
            return redirect()->route('cms-pages.index');
        } catch (\Throwable $e) {
            $message = "Error while storing Cms Page";
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
            return redirect()->route('cms-pages.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $action = $this->cmsPageService->fetchCmsPage($id);
            $cmsPage = $action->data;
            return view('Pages.CmsPage.update', ['data' => $cmsPage]);
        } catch (\Throwable $e) {
            $message = "Error while fetching CMS Page data";
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCmsPageRequest $request, $id)
    {
        try {
            $action = $this->cmsPageService->updateCmsPage($request, $id);
            $this->toasterService->toast($action);
            return redirect()->route('cms-pages.index');
        } catch (\Throwable $e) {
            $message = "Error while updating CMS Page";
            $this->toasterService->exceptionToast($message);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsPage $cmsPage)
    {
        try {
            $action = $this->cmsPageService->deleteCmsPage($cmsPage->id);
            $this->toasterService->toast($action);
            return redirect()->back();
        } catch (\Throwable $e) {
            $message = "Error while deleting Cms Page";
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }
}
