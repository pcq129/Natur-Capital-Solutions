<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Enums\Status;
use Yajra\DataTables\DataTables;
use App\Constants\ServiceConstants as CONSTANTS;
use App\Http\Requests\Service\StoreServiceRequest;

class ServiceController extends Controller
{
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
                    return '<img src='.$row.' height="100px" width="100px">';
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
        $data = $request->validated();
        
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
