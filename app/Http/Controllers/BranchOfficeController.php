<?php

namespace App\Http\Controllers;

use App\Enums\ServiceResponseType;
use App\Enums\ServiceStatus;
use App\Enums\Status;
use App\Models\BranchOffice;
use Illuminate\Http\Request;
use App\Services\BranchOfficeService;
use App\Http\Requests\BranchOffice\CreateBranchOfficeRequest;
use App\Http\Requests\BranchOffice\UpdateBranchOfficeRequest;
use App\Services\ToasterService;

class BranchOfficeController extends Controller
{

    public function __construct(private BranchOfficeService $branchOfficeService, private ToasterService $toasterService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $action = $this->branchOfficeService->fetchBranchOffices();
        return view('Pages.BranchOffice.index', ['data' => $action->data]);
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
        $branchOffice = $request->validated();
        $action = $this->branchOfficeService->createBranchOffice($branchOffice);
        $this->toasterService->toast($action);
        return redirect()->route('branchoffices.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $action = $this->branchOfficeService->getSingleBranchOffice($id);
        if ($action->status == ServiceResponseType::Success) {
            return view('Pages.BranchOffice.update', ['data' => $action->data]);
        } else {
            $this->toasterService->toast($action);
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchOfficeRequest $request, $id)
    {
        $action = $this->branchOfficeService->updateBranchOffice($request, $id);
        $this->toasterService->toast($action);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $action = $this->branchOfficeService->deleteBranchOffice($id);
        $this->toasterService->toast($action);
        return redirect()->back();
    }
}
