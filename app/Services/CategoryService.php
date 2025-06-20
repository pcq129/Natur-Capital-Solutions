<?php

namespace App\Services;

use App\Http\Controllers\Admin\CategoryController;
use App\Services\DTO\ServiceResponse;
use App\Enums\ServiceResponseType;
use App\Enums\Status;
use App\Models\Category;
use App\Constants\CategoryConstants as CONSTANTS;
use Yajra\DataTables\Facades\DataTables;
use App\Exceptions\Handler;


class CategoryService
{
    public function createCategory($request)
    {
        try {
            Category::create([
                'name' => $request['name'],
                'status' => Status::ACTIVE
            ]);
            return ServiceResponse::success(CONSTANTS::STORE_SUCCESS);
        } catch (\Throwable $th) {
            $message = CONSTANTS::STORE_SUCCESS;
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function updateCategory(Category $category, $request)
    {
        try {
            $category->fill([
                'name' => $request['name'],
                'status' => $request['status']
            ]);
            if ($category->isDirty()) {
                $category->save();
                return ServiceResponse::success(CONSTANTS::UPDATE_SUCCESS);
            } else {
                return ServiceResponse::error(CONSTANTS::NO_CHANGE);
            }
        } catch (\Throwable $th) {
            $message = CONSTANTS::UPDATE_SUCCESS;
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function fetchCategories($request)
    {
        try {

            $query = Category::query()->orderBy('id', 'DESC');

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
                        return 'Error';
                    }
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('categories.edit', $row->id);
                    $targetDelete = CONSTANTS::DELETE_CATEGORY_MODAL;
                    $targetEdit = CONSTANTS::UPDATE_CATEGORY_MODAL;
                    return view('Partials.Category.actions', ['edit' => $editUrl,  'row' => $row, 'targetDelete' => $targetDelete, 'targetEdit' => $targetEdit]);
                })
                ->rawColumns(['actions'])
                ->make(true);

            return ServiceResponse::success(CONSTANTS::FETCH_SUCCESS,  $categories);
        } catch (\Throwable $th) {
            $message = CONSTANTS::STORE_SUCCESS;
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    public function deleteCategory(Category $category)
    {
        try {
            $is_deleted = $category->delete();
            if ($is_deleted) {
                return ServiceResponse::success(CONSTANTS::DELETE_SUCCESS);
            } else {
                return ServiceResponse::error(CONSTANTS::DELETE_FAIL);
            }
        } catch (\Throwable $th) {
            $message = CONSTANTS::STORE_SUCCESS;
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }
}
