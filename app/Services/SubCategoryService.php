<?php

namespace App\Services;

use App\Http\Controllers\Admin\SubCategoryController;
use App\Services\DTO\ServiceResponse;
use App\Enums\ServiceResponseType;
use App\Enums\Status;
use App\Models\SubCategory;
use App\Constants\SubCategoryConstants as CONSTANTS;
use Yajra\DataTables\Facades\DataTables;


class SubCategoryService
{
    public function createSubCategory($request){
        SubCategory::create([
            'name' => $request['name'],
            'status' => Status::ACTIVE
        ]);
        return ServiceResponse::success(CONSTANTS::STORE_SUCCESS);
    }

    public function updateSubCategory(SubCategory $subCategory, $request){
        $subCategory->fill([
            'name' => $request['name'],
            'status' => $request['status']
        ]);
        if($subCategory->isDirty()){
            $subCategory->save();
            return ServiceResponse::success(CONSTANTS::UPDATE_SUCCESS);
        }else{
            return ServiceResponse::error(CONSTANTS::NO_CHANGE);
        }
    }

    public function fetchSubCategories($request){
        $query = SubCategory::query();

        if($request->filled('status')){
            $query->where('status', (int) $request->status);
        }

        $categories = DataTables::of($query)
            ->addColumn('status', function ($row) {
                return $row->status == Status::ACTIVE->value ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('categories.edit', $row->id);
                $targetDelete = CONSTANTS::DELETE_CATEGORY_MODAL;
                $targetEdit = CONSTANTS::UPDATE_CATEGORY_MODAL;
                return view('Partials.Category.actions', ['edit' => $editUrl,  'row' => $row, 'targetDelete' => $targetDelete, 'targetEdit'=>$targetEdit]);
            })
            ->rawColumns(['actions'])
            ->make(true);

        return ServiceResponse::success("Categories fetched successfully",  $categories);
    }

    public function deleteSubCategory(SubCategory $subCategory){
        $is_deleted = $subCategory->delete();
        if($is_deleted){
            return ServiceResponse::success("Sub-Category deleted successfully");
        }else{
            return ServiceResponse::error("Error while deleting Sub-Category");
        }
    }
}