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
                $editUrl = route('products.edit', $row->id);
                $targetDelete = CONSTANTS::PRODUCT_DELETE_MODAL_ID;
                return view('Partials.actions', ['edit' => $editUrl,  'row' => $row, 'target' => $targetDelete]);
            })
            ->rawColumns(['actions'])
            ->make(true);

        return ServiceResponse::success('success' , $products);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request)
    {
        // dd($request);
        $product = Product::create([
            'name' => $request['name'],
            'is_featured' => $request['isFeatured'] ?? false,
            'category_id' => $request['productCategory'],
            'sub_category_id' => $request['productSubCategory'],
            'minimum_quantity' => $request['minimumQuantity'],
            'is_featured' => $request['is_featured'] ?? false,
            'status' => Status::from($request['status']??true),
            'description' => $request['product-trixFields'][CONSTANTS::PRODUCT_DESCRIPTION] ?? null,
        ]);

        $product->sections()->createMany([
            [
                'priority' => $request['descriptionPriority'] ?? 0,
                'name' => CONSTANTS::PRODUCT_DESCRIPTION,
                'content' => $request['product-trixFields'][CONSTANTS::PRODUCT_DESCRIPTION] ?? null,
            ],
            [
                'priority' => $request['informationPriority'] ?? 0,
                'name' => CONSTANTS::PRODUCT_INFORMATION,
                'content' => $request['product-trixFields'][CONSTANTS::PRODUCT_INFORMATION] ?? null,
            ],
            [
                'priority' => $request['characteristicsPriority'] ?? 0,
                'name' => CONSTANTS::PRODUCT_CHARACTERISTICS,
                'content' => $request['product-trixFields'][CONSTANTS::PRODUCT_CHARACTERISTICS] ?? null,
            ],
            [
                'priority' => $request['warrantyListPriority'] ?? 0,
                'name' => CONSTANTS::PRODUCT_WARRANTY_LIST,
                'content' => $request['product-trixFields'][CONSTANTS::PRODUCT_WARRANTY_LIST] ?? null,
            ],
            [
                'priority' => $request['serviceListPriority'] ?? 0,
                'name' => CONSTANTS::PRODUCT_SERVICE_LIST,
                'content' => $request['product-trixFields'][CONSTANTS::PRODUCT_SERVICE_LIST] ?? null,
            ],
        ]);

        return ServiceResponse::success(CONSTANTS::STORE_SUCCESS, $product->id);
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
