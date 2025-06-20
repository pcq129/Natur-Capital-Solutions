<?php

namespace App\Services;

use App\Models\Product;
use App\Enums\Status;
use App\Enums\Language;
use App\Enums\ServiceResponseType;
use App\Exceptions\Handler;
use App\Services\DTO\ServiceResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Constants\ProductConstants as CONSTANTS;
use Illuminate\Http\Request;


class ProductService
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query()->orderBy('id', 'DESC');

        if ($request->filled('status')) {
            $query->where('status', (int)$request->status);
        }

        $products = DataTables::of($query)
            ->addColumn('status', function ($row) {
                if ($row->status->value == Status::ACTIVE->value) {
                    return 'Active';
                } else if ($row->status->value == Status::INACTIVE->value) {
                    return 'Inactive';
                }
            })
            ->addColumn('category', function ($row) {
                return $row->category->name ?? '-';
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('categories.edit', $row->id);
                $targetDelete = 'CONSTANTS::DELETE_SUB_CATEGORY_MODAL';
                $targetEdit = 'CONSTANTS::UPDATE_SUB_CATEGORY_MODAL';
                return view('Partials.Category.actions', ['edit' => $editUrl,  'row' => $row, 'targetDelete' => $targetDelete, 'targetEdit' => $targetEdit]);
            })
            ->rawColumns(['actions'])
            ->make(true);

        return ServiceResponse::success('success' , $products);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
