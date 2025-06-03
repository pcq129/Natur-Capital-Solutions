<?php

namespace App\Http\Controllers;

use App\Models\BranchOffice;
use Illuminate\Http\Request;
use App\Services\BranchOfficeService;
use App\Http\Requests\BranchOffice\CreateBranchOfficeRequest;
use App\Http\Requests\BranchOffice\UpdateBranchOfficeRequest;
use App\Services\ToasterService;

class BranchOfficeController extends Controller
{

    public function __construct(private BranchOfficeService $branchOfficeService, private ToasterService $toasterService){}

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
     * Display the specified resource.
     */
    public function show(BranchOffice $branchOffice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BranchOffice $branchOffice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BranchOffice $branchOffice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchOffice $branchOffice)
    {
        //
    }
}
