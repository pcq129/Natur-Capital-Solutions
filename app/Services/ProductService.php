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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FileService;
use App\Models\ProductFile;
use App\Enums\FileType;


class ProductService
{

    public function __construct(protected FileService $fileService)
    {
        // Constructor to inject dependencies
    }

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

        return ServiceResponse::success('success', $products);
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
            'status' => Status::from($request['status'] ?? true),
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
    public function update(array $data, Product $product)
    {
        try {
            // DB::transaction(function () use ($product, $data) {

                $product->sections()->delete();

                $product->sections()->createMany([
                    [
                        'priority' => $data['descriptionPriority'] ?? 0,
                        'name' => CONSTANTS::PRODUCT_DESCRIPTION,
                        'content' => $data['product-trixFields'][CONSTANTS::PRODUCT_DESCRIPTION] ?? null,
                    ],
                    [
                        'priority' => $data['informationPriority'] ?? 0,
                        'name' => CONSTANTS::PRODUCT_INFORMATION,
                        'content' => $data['product-trixFields'][CONSTANTS::PRODUCT_INFORMATION] ?? null,
                    ],
                    [
                        'priority' => $data['characteristicsPriority'] ?? 0,
                        'name' => CONSTANTS::PRODUCT_CHARACTERISTICS,
                        'content' => $data['product-trixFields'][CONSTANTS::PRODUCT_CHARACTERISTICS] ?? null,
                    ],
                    [
                        'priority' => $data['warrantylistPriority'] ?? 0,
                        'name' => CONSTANTS::PRODUCT_WARRANTY_LIST,
                        'content' => $data['product-trixFields'][CONSTANTS::PRODUCT_WARRANTY_LIST] ?? null,
                    ],
                    [
                        'priority' => $data['servicelistPriority'] ?? 0,
                        'name' => CONSTANTS::PRODUCT_SERVICE_LIST,
                        'content' => $data['product-trixFields'][CONSTANTS::PRODUCT_SERVICE_LIST] ?? null,
                    ],
                ]);
                $productId = $product->id;



                if (isset($data['productImage'])) {

                    $productImage = $this->fileService->saveFile(
                        $data['productImage'],
                        CONSTANTS::PRODUCT_IMAGE_PATH
                    );

                    $oldfiles = ProductFile::where('product_id', $productId)
                        ->where('file_type', FileType::MAIN_IMAGE->value)
                        ->get();


                    ProductFile::create([
                        'product_id' => $productId,
                        'file_name' =>  $data['productImage']->getClientOriginalName(),
                        'file_path' => $productImage->data,
                        'file_type' => FileType::MAIN_IMAGE->value,
                    ]);

                    foreach ($oldfiles as $singleFile) {
                        $action = $this->fileService->deleteFile($singleFile->file_path);
                        if($action->status == ServiceResponseType::SUCCESS){
                            $singleFile->delete();
                            logger()->info('Old product image deleted successfully, file path: ' . $singleFile->file_path);
                        }else{
                            logger()->error('Failed to delete old product image, file path: ' . $singleFile->file_path);
                            dd('Failed to delete old product image, file path: ' . $singleFile->file_path);
                            return;
                        }

                    }
                }

                if (isset($data['videoInstruction'])) {

                    $productVideo = $this->fileService->saveFile(
                        $data['videoInstruction'],
                        CONSTANTS::PRODUCT_FILE_PATH
                    );

                    $oldFiles = ProductFile::where('product_id', $productId)
                        ->where('file_type', FileType::VIDEO->value)
                        ->get();

                    ProductFile::create([
                        'product_id' => $productId,
                        'file_name' =>  $data['videoInstruction']->getClientOriginalName(),
                        'file_path' => $productVideo->data,
                        'file_type' => FileType::VIDEO->value,
                    ]);
                    foreach ($oldFiles as $singleFile) {
                        $this->fileService->deleteFile($singleFile->file_path);
                        $singleFile->delete();
                        logger()->info('Old product image deleted successfully, file path: ' . $singleFile->file_path);
                    }
                }

                if (isset($data['productDetailImages'])) {
                    $oldFiles = ProductFile::where('product_id', $productId)
                        ->where('file_type', FileType::IMAGE->value)
                        ->get();

                    foreach ($data['productDetailImages'] as $image) {
                        $productDetailImage = $this->fileService->saveFile(
                            $image,
                            CONSTANTS::PRODUCT_IMAGE_PATH
                        );

                        ProductFile::create([
                            'product_id' => $productId,
                            'file_name' => $image->getClientOriginalName(),
                            'file_path' => $productDetailImage->data,
                            'file_type' => FileType::IMAGE->value,
                        ]);
                    }

                    foreach ($oldFiles as $singleFile) {
                        $this->fileService->deleteFile($singleFile->file_path);
                        $singleFile->delete();
                        logger()->info('Old product image deleted successfully, file path: ' . $singleFile->file_path);
                    }
                }
                if (isset($data['files'])) {
                    $oldDocuments = ProductFile::where('product_id', $productId)
                        ->where('file_type', FileType::PDF->value)
                        ->get();

                    foreach ($data['files'] as $document) {
                        $productDocument = $this->fileService->saveFile(
                            $document,
                            CONSTANTS::PRODUCT_FILE_PATH
                        );

                        ProductFile::create([
                            'product_id' => $productId,
                            'file_name' => $document->getClientOriginalName(),
                            'file_path' => $productDocument->data,
                            'file_type' => FileType::PDF->value,
                        ]);
                    }
                    foreach ($oldDocuments as $document) {
                        $this->fileService->deleteFile($document->file_path);
                        $document->delete();
                    }
                }
                return ServiceResponse::success(CONSTANTS::UPDATE_SUCCESS, $product->id);
            // });
        } catch (\Throwable $th) {
            $message = "Uncaught exception while updating product";
            Handler::logError($th, $message);
            return ServiceResponse::error($message);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $productFiles = $product->ProductFiles()->get();
        $productSections = $product->Sections()->get();


        foreach($productFiles as $file){
            $fileLocation = $file->file_path;
            $deleteStatus = $this->fileService->deleteFile($fileLocation);

            if($deleteStatus->status){
                $file->delete();
            }
        }
        foreach($productSections as $section){
            $section->delete();
        }
        $product->delete();

        return ServiceResponse::success('Product deleted successfully');

    }
}
