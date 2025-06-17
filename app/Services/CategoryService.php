<?php

namespace App\Services;

use App\Http\Controllers\Admin\CategoryController;
use App\Services\DTO\ServiceResponse;
use App\Enums\ServiceResponseType;
use App\Enums\Status;
use App\Models\Category;
use App\Constants\CategoryConstants as CONSTANTS;
use Yajra\DataTables\Facades\DataTables;


class CategoryService
{
    public function createCategory($request){
        Category::create([
            'name' => $request['name'],
            'status' => Status::ACTIVE
        ]);
        return ServiceResponse::success(CONSTANTS::STORE_SUCCESS);
    }

    public function updateCategory(Category $category, $request){
        $category->fill([
            'name' => $request['name'],
            'status' => $request['status']
        ]);
        if($category->isDirty()){
            $category->save();
            return ServiceResponse::success(CONSTANTS::UPDATE_SUCCESS);
        }else{
            return ServiceResponse::error(CONSTANTS::NO_CHANGE);
        }
    }

    public function fetchCategories($request){
        $query = Category::query();

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

    public function deleteCategory(Category $category){
        $is_deleted = $category->delete();
        if($is_deleted){
            return ServiceResponse::success("Category deleted successfully");
        }else{
            return ServiceResponse::error("Error while deleting category");
        }
    }
}