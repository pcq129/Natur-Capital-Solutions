<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\BranchOffice;
use App\Services\DTO\ServiceResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\BranchOffice\UpdateBranchOfficeRequest;

class BranchOfficeService
{

    public function fetchBranchOffices($request): ServiceResponse
    {

        $query = BranchOffice::query();

        if($request->filled('status')){
            $query->where('status', (int) $request->status);
        }

        $branchOffices = DataTables::of($query)
            ->addColumn('status', function ($row) {
                return $row->status == Status::Active ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('branchoffices.edit', $row->id);
                return view('Pages.BranchOffice.Partials.actions', ['edit' => $editUrl,  'row' => $row]);
            })
            ->rawColumns(['actions'])
            ->make(true);

        return ServiceResponse::success("Branches fetched successfully",  $branchOffices);
    }

    public function createBranchOffice($request): ServiceResponse
    {
        // dd($request);
        $branchOffice = BranchOffice::create([
            'name' => $request['name'],
            'address' => $request['address'],
            'email' => $request['email'],
            'mobile' => $request['mobile'],
            'location' => $request['location'],
            'status' => Status::Active
        ]);
        if ($branchOffice->wasRecentlyCreated) {
            return ServiceResponse::success("Branch Office created successfully");
        } else {
            return ServiceResponse::error("Error while adding Branch Office");
        }
    }

    public function updateBranchOffice(UpdateBranchOfficeRequest $request, int $id)
    {
        $updatedBranchOffice = $request->validated();
        $branchOffice = BranchOffice::find($id);
        if (!$branchOffice) {
            return ServiceResponse::error('Branch not found');
        }
        $branchOffice->fill([
            'name' => $updatedBranchOffice['name'],
            'address' => $updatedBranchOffice['address'],
            'email' => $updatedBranchOffice['email'],
            'mobile' => $updatedBranchOffice['mobile'],
            'location' => $updatedBranchOffice['location'],
            'status' => $updatedBranchOffice['status']
        ]);

        if ($branchOffice->isDirty()) {
            $branchOffice->save();
            return ServiceResponse::success('Branch updated successfully');
        } else {
            return ServiceResponse::info('No changes detected');
        }
    }

    public function deleteBranchOffice(int $id)
    {
        $branchOffice = BranchOffice::find($id);
        if ($branchOffice) {
            $branchOffice->delete();
            return ServiceResponse::success('Branch deleted successfully');
        } else {
            return ServiceResponse::error('Branch not found');
        }
    }

    public function getSingleBranchOffice($id): ServiceResponse
    {
        $branchOffice = BranchOffice::find($id);
        if ($branchOffice) {
            return ServiceResponse::success("Branch fetched successfully", $branchOffice);
        } else {
            return ServiceResponse::error("Branch not found");
        }
    }
}
