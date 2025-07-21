<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Enums\Status;
use Yajra\DataTables\DataTables;
use App\Constants\ServiceConstants as CONSTANTS;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Services\ServiceService;
use App\Services\ToasterService;

class ServiceController extends Controller
{
    public function __construct(private ServiceService $serviceService, private ToasterService $toasterService)
    {
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Service::query();

            $services = DataTables::of($query)
                ->addColumn('status', function ($row) {
                    if ($row->status->value == Status::ACTIVE->value) {
                        return 'Active';
                    } else if ($row->status->value == Status::INACTIVE->value) {
                        return 'Inactive';
                    }
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('services.edit', $row->id);
                    $targetDelete = CONSTANTS::SERVICE_DELETE_MODAL_ID;
                    return view('Partials.actions', ['edit' => $editUrl,  'row' => $row, 'target' => $targetDelete]);
                })
                ->addColumn('icon', function($row){
                    return '<img src="'.$row->icon.'" height="100px" width="100px">';
                })
                ->rawColumns(['actions','icon'])
                ->make(true);

                return $services;
        }

        return view('Pages.Services.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Pages.Services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $texts = $request->validated();
        $files = $request->allFiles();

        $data = array_merge($texts, $files);
        $action = $this->serviceService->StoreService($data);
        $this->toasterService->toast($action);
        return response()->json([
            'status' => 'success',
            'message' => $action->message,
            'redirect' => route('services.index')
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $serviceSections = $service->serviceSections()->get(['id', 'service_id', 'heading', 'content', 'priority']);
        return view('Pages.Services.update', ['service' => $service, 'serviceSections' => $serviceSections]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {

        $data = $request->validated();
        $action = $this->serviceService->UpdateService($data, $service);
        $this->toasterService->toast($action);
        return response()->json([
            'status' => 'success',
            'message' => $action->message,
            'redirect' => route('services.index')
        ], 200);
        return redirect()->route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $action = $this->serviceService->DeleteService($service);
        $this->toasterService->toast($action);
        return redirect()->route('services.index');
    }
}
