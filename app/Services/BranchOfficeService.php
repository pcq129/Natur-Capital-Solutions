<?php

namespace App\Services;

use App\Enums\ServiceResponseType;
use App\Enums\Status;
use App\Models\BranchOffice;
use App\Services\DTO\ServiceResponse;
use App\Traits\Validations\BaseBannerValidationRules;

class BranchOfficeService
{

    // public function __construct(private ServiceResposne $serviceResponse){}

    public function fetchBranchOffices($pageLength = null): ServiceResponse
    {
        $branchOffices = BranchOffice::orderBy('id', 'desc')->paginate($pageLength ?? 5);
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
        }else{
            return ServiceResponse::error("Error while adding Branch Office");
        }
    }

    public function updateBranchOffice() {}

    public function deleteBranchOffice() {}

    public function getSingleBranchOffice() {}
}
