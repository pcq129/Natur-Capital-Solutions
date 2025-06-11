<?php

namespace App\Services;

use App\Http\Requests\CmsPage\CreateCmsPageRequest;
use App\Http\Requests\CmsPage\UpdateCmsPageRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CmsPage;
use App\Enums\Status;
use Illuminate\Http\Request;
use App\Exceptions\Handler;
use App\Services\DTO\ServiceResponse;

class CmsPageService
{

    public function indexCmsPage(Request $request)
    {
        try {
            $query = CmsPage::query();

            if($request->filled('status')){
                $query->where('status', (int) $request->status);
            }

            $cmsPageData = DataTables::of($query)
                ->addColumn('status', function ($row) {
                    return $row->status == Status::ACTIVE ? 'Active' : 'Inactive';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('cms-pages.edit', $row->id);
                    $target = CmsPage::DELETE_MODAL_ID;
                    return view('Partials.actions', ['edit' => $editUrl,  'row' => $row, 'target' => $target])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);

            return ServiceResponse::success('CMS pages fetched successfully', $cmsPageData);
        } catch (\Throwable $th){
            $message = "Error while fetching CMS Pages";
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function createCmsPage(CreateCmsPageRequest $request): ServiceResponse
    {

        $successMessage = "CMS Page created successfully";
        $failureMessage = "Error while creating CMS page";


        try {
            $cmsPage = $request->only(
                'name',
                'language',
                'cmspage-trixFields'
            );

            CmsPage::create([
                'name' => $cmsPage['name'],
                'language' => $cmsPage['language'],
                'content' => $cmsPage['cmspage-trixFields']['cmsText'],
                'cmspage-trixFields' =>  $cmsPage['cmspage-trixFields'],
                'status' => Status::ACTIVE
            ]);

            return ServiceResponse::success($successMessage);
        } catch (\Throwable $e) {
            Handler::logError($e, $failureMessage);
            return ServiceResponse::error($failureMessage);
        }
    }

    public function updateCmsPage(UpdateCmsPageRequest $request, $id)
    {
        try {
            $cmsPage = CmsPage::findOrFail($id);
            $newCmsPageData = $request->validated();
            $cmsPage->fill([
                'name' => $newCmsPageData['name'],
                'language' => $newCmsPageData['language'],
                'content' => $newCmsPageData['cmspage-trixFields']['cmsText'],
                'cmspage-trixFields' =>  $newCmsPageData['cmspage-trixFields'],
                'status' => $newCmsPageData['status'] ? Status::ACTIVE : Status::INACTIVE
            ]);

            if ($cmsPage->isDirty()) {
                $cmsPage->save();
                return ServiceResponse::success('CMS Page updated successfully');
            } else {
                return ServiceResponse::info('No changes detected');
            }
        } catch (\Throwable $th) {
            $message = "Error while updating CMS Page";
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function fetchCmsPage($id)
    {
        try {
            $cmsPage = CmsPage::find($id);
            return ServiceResponse::success('success', $cmsPage);
        } catch (\Throwable $th) {
            $message = "Error while fetching CMS Page";
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function deleteCmsPage($id)
    {
        try {
            $cmsPage = CmsPage::findOrFail($id);
            $cmsPage->delete();
            return ServiceResponse::success('CMS Page deleted successfully');
        } catch (ModelNotFoundException $e) {
            return ServiceResponse::error('CMS Page not found', 404);
        } catch (\Exception $e) {
            $message = 'Error occurred while deleting CMS Page';
            Handler::logError($e, $message);
            return ServiceResponse::error($message);
        }
    }
}
