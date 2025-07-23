<?php

namespace App\Services;

use App\Enums\Status;
use App\Services\DTO\ServiceResponse;
use App\Models\BranchOffice;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\BranchOffice\UpdateBranchOfficeRequest;

class BranchOfficeService
{

    public function fetchBranchOffices($request): ServiceResponse
    {

        $query = BranchOffice::query()->orderBy('id', 'DESC');

        if($request->filled('status')){
            $query->where('status', (int) $request->status);
        }

        $branchOffices = DataTables::of($query)
            ->addColumn('status', function ($row) {
                return $row->status == Status::ACTIVE ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('branchoffices.edit', $row->id);
                $target = BranchOffice::BRANCH_DELETE_ID;
                return view('Partials.actions', ['edit' => $editUrl,  'row' => $row, 'target' => $target]);
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
            'status' => Status::ACTIVE
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
