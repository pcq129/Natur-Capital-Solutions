<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BranchOfficeService;
use App\Http\Requests\BranchOffice\CreateBranchOfficeRequest;
use App\Http\Requests\BranchOffice\UpdateBranchOfficeRequest;
use App\Services\ToasterService;
use App\Exceptions\Handler;
use App\Http\Controllers\Controller;

class BranchOfficeController extends Controller
{

    public function __construct(private BranchOfficeService $branchOfficeService, private ToasterService $toasterService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $action = $this->branchOfficeService->fetchBranchOffices($request);
                return $action->data;
            } else {
                return view('Pages.BranchOffice.index');
            }
        } catch (\Exception $e) {
            $message = "Error while fetching Branch Offices";
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Pages.BranchOffice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBranchOfficeRequest $request)
    {
        try {
            $branchOffice = $request->validated();
            $action = $this->branchOfficeService->createBranchOffice($branchOffice);
            $this->toasterService->toast($action);
            return redirect()->route('branchoffices.index');
        } catch (\Throwable $e) {
            $message = "Error creating Branch Office";
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $action = $this->branchOfficeService->getSingleBranchOffice($id);
            $branchOfficeData = $action->data;
            if($branchOfficeData){
                return view('Pages.BranchOffice.update', ['data' => $branchOfficeData]);
            }else{
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            $message = "Error while fetching Branch data for editing";
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchOfficeRequest $request, $id)
    {
        try {
            $action = $this->branchOfficeService->updateBranchOffice($request, $id);
            $this->toasterService->toast($action);
            return redirect()->back();
        } catch (\Throwable $e) {
            $message = "Error while updating Branch Office";
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $action = $this->branchOfficeService->deleteBranchOffice($id);
            $this->toasterService->toast($action);
            return redirect()->back();
        } catch (\Throwable $e) {
            $message = "Error while deleting Branch Office";
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
        }
    }
}
