<?php

namespace App\Services;

use App\Http\Controllers\Admin\SubCategoryController;
use App\Services\DTO\ServiceResponse;
use App\Enums\ServiceResponseType;
use App\Enums\Status;
use App\Models\SubCategory;
use App\Constants\SubCategoryConstants as CONSTANTS;
use Yajra\DataTables\Facades\DataTables;
use App\Exceptions\Handler;

class SubCategoryService
{
    public function createSubCategory($request)
    {

        try {
            SubCategory::create([
                'name' => $request['name'],
                'status' => Status::ACTIVE,
                'category_id' => $request['category_id']
            ]);
            return ServiceResponse::success(CONSTANTS::STORE_SUCCESS);
        } catch (\Throwable $th) {
            $message = CONSTANTS::STORE_FAIL;
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function updateSubCategory( $subCategory, $data)
    {
        try {
            $subCategory->fill([
                'name' => $data['name'],
                'status' => $data['status'],
                'category_id' => $data['category_id']
            ]);
            if ($subCategory->isDirty()) {
                $subCategory->save();
                return ServiceResponse::success(CONSTANTS::UPDATE_SUCCESS);
            } else {
                return ServiceResponse::error(CONSTANTS::NO_CHANGE);
            }
        } catch (\Throwable $th) {
            $message = CONSTANTS::UPDATE_FAIL;
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function fetchSubCategories($request)
    {
        try {
            $query = SubCategory::with('category:id,name')->orderBy('id', 'DESC');

            if ($request->filled('status')) {
                $query->where('status', (int) $request->status);
            }

            $categories = DataTables::of($query)
                ->addColumn('status', function ($row) {
                    if ($row->status->value == Status::ACTIVE->value) {
                        return 'Active';
                    } else if ($row->status->value == Status::INACTIVE->value) {
                        return 'Inactive';
                    } else {
                        return CONSTANTS::STATUS_ERROR;
                    }
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('categories.edit', $row->id);
                    $targetDelete = CONSTANTS::DELETE_SUB_CATEGORY_MODAL;
                    $targetEdit = CONSTANTS::UPDATE_SUB_CATEGORY_MODAL;
                    return view('Partials.Category.actions', ['edit' => $editUrl,  'row' => $row, 'targetDelete' => $targetDelete, 'targetEdit' => $targetEdit]);
                })
                ->rawColumns(['actions'])
                ->make(true);

            return ServiceResponse::success(CONSTANTS::FETCH_SUCCESS,  $categories);
        } catch (\Throwable $th) {
            $message = CONSTANTS::FETCH_FAIL;
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function deleteSubCategory(SubCategory $subCategory)
    {
        try {
            $is_deleted = $subCategory->delete();
            if ($is_deleted) {
                return ServiceResponse::success(CONSTANTS::DELETE_SUCCESS);
            } else {
                return ServiceResponse::error(CONSTANTS::DELETE_FAIL);
            }
        } catch (\Throwable $th) {
            $message = CONSTANTS::DELETE_FAIL;
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }
}
