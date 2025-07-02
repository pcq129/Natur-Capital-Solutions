<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Enums\Status;
use App\Constants\AppConstants;
use App\Constants\ProductConstants;
use App\Enums\FileType;
use App\Services\ProductService;
use App\Services\ToasterService;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductFile;
use App\Models\SubCategory;
use App\Services\FileService;
use Illuminate\Support\Facades\Validator;
use App\Constants\ProductConstants as CONSTANTS;
use App\Enums\ServiceResponseType;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService, private ToasterService $toasterService, private FileService $fileService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $action = $this->productService->index($request);
            return $action->data;
        } else {
            return view('Pages.Product.index');
        }
    }

    // public function addFiles(Request $request, $product ){
    //     dd($request);
    //     $product = Product::findOrFail($request->product);
    //     dd($product, $request->file('productImage'));
    // }

    // public function addImages(Request $request, $product)
    // {

    //     $productImage = $this->fileService->saveFile(
    //         $request->file('productImage'),
    //         ProductConstants::PRODUCT_IMAGE_PATH
    //     );

    //     ProductFile::create([
    //         'product_id' => $product,
    //         'file_name' =>  $request->file('productImage')->getClientOriginalExtension(),
    //         'file_path' => $productImage->data,
    //         'file_type' => FileType::MAIN_IMAGE->value,
    //     ]);

    //     foreach ($request->file('productDetailImages') as $image) {
    //         $productDetailImage = $this->fileService->saveFile(
    //             $image,
    //             ProductConstants::PRODUCT_IMAGE_PATH
    //         );

    //         ProductFile::create([
    //             'product_id' => $product,
    //             'file_name' => $image->getClientOriginalExtension(),
    //             'file_path' => $productDetailImage->data,
    //             'file_type' => FileType::IMAGE->value,
    //         ]);
    //     }
    //     toastr()->success('Product images added successfully');
    //     return view('Pages.Product.add-files', ['productId' => $product]);

    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('Pages.Product.create-product', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'productCategory' => 'required|integer|exists:categories,id',
            'productSubCategory' => 'required|integer|exists:sub_categories,id',
            'minimumQuantity' => 'required|integer|min:1',
            'productImage' => 'required|image|mimes:jpg,jpeg,png|max:8192',
            'productDetailImages.*' => 'required|image|mimes:jpg,jpeg,png|max:8192',
            'videoInstruction' => 'nullable|mimes:mp4|max:51200', // 50MB
            'files.*' => 'required|mimes:pdf|max:8192',
            'product-trixFields' => 'required|array',
            'product-trixFields.*' => ['required', function ($attribute, $value, $fail) {
                if (trim(strip_tags($value)) === '') {
                    $fail("The $attribute field cannot be empty.");
                }
            }],
            // Validate Trix inputs
            'descriptionPriority' => 'required|integer|min:1|max:5',
            'informationPriority' => 'required|integer|min:1|max:5',
            'characteristicsPriority' => 'required|integer|min:1|max:5',
            'warrantyListPriority' => 'required|integer|min:1|max:5',
            'serviceListPriority' => 'required|integer|min:1|max:5',
        ]);


        $action = $this->productService->store($data);
        $productId = $action->data; //
        $productImage = $this->fileService->saveFile(
            $request->file('productImage'),
            ProductConstants::PRODUCT_IMAGE_PATH
        );
        $productVideo = $this->fileService->saveFile(
            $request->file('videoInstruction'),
            ProductConstants::PRODUCT_FILE_PATH
        );

        ProductFile::create([
            'product_id' => $productId,
            'file_name' =>  $request->file('productImage')->getClientOriginalName(),
            'file_path' => $productImage->data,
            'file_type' => FileType::MAIN_IMAGE->value,
        ]);

        ProductFile::create([
            'product_id' => $productId,
            'file_name' =>  $request->file('videoInstruction')->getClientOriginalName(),
            'file_path' => $productVideo->data,
            'file_type' => FileType::VIDEO->value,
        ]);

        foreach ($request->file('productDetailImages') as $image) {
            $productDetailImage = $this->fileService->saveFile(
                $image,
                ProductConstants::PRODUCT_IMAGE_PATH
            );

            ProductFile::create([
                'product_id' => $productId,
                'file_name' => $image->getClientOriginalName(),
                'file_path' => $productDetailImage->data,
                'file_type' => FileType::IMAGE->value,
            ]);
        }
        foreach ($request->file('files') as $document) {
            $productDocument = $this->fileService->saveFile(
                $document,
                ProductConstants::PRODUCT_FILE_PATH
            );

            ProductFile::create([
                'product_id' => $productId,
                'file_name' => $image->getClientOriginalName(),
                'file_path' => $productDocument->data,
                'file_type' => FileType::PDF->value,
            ]);
        }


        // $this->toasterService->toast($action);
        return response()->json([
            'message' => 'Product created successfully',
            'productId' => $productId,
        ], 201);
    }

    /**
     * Show the form for adding files to a product.
     */
    // public function addImagesForm(Request $request)
    // {
    //     $productId = $request->query('product');
    //     return view('Pages.Product.add-images', compact('productId'));
    // }


    /**
     * Show the form for adding files to a product.
     */
    // public function addFilesForm(Request $request)
    // {
    //     $productId = $request->query('product');
    //     return view('Pages.Product.add-files', compact('productId'));
    // }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product)
    {
        $categories = Category::all();
        $data = Product::where('id', $product)->with('Sections', 'ProductFiles')->get();
        $product = $data;
        // dd($product[0]->Sections);
        return view('Pages.Product.edit-product', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // dd($request->all(), $request->files->all());


        // dd($request->all());

        // $data = $request->validate([
        //     'name' => 'required|string|max:255|unique:products,name',
        //     'productCategory' => 'required|integer|exists:categories,id',
        //     'productSubCategory' => 'required|integer|exists:sub_categories,id',
        //     'minimumQuantity' => 'required|integer|min:1',
        //     'productImage' => 'image|mimes:jpg,jpeg,png|max:8192|mimes:jpg,jpeg,png|max:8192',
        //     'productDetailImages.*' => 'image|mimes:jpg,jpeg,png|max:8192',
        //     'videoInstruction' => 'nullable|mimes:mp4|max:51200', // 50MB
        //     'files.*' => 'mimes:pdf|max:8192',
        //     'product-trixFields' => 'required|array',
        //     'product-trixFields.*' => ['required', function ($attribute, $value, $fail) {
        //         if (trim(strip_tags($value)) === '') {
        //             $fail("The $attribute field cannot be empty.");
        //         }
        //     }],
        //     'descriptionPriority' => 'required|integer|min:1|max:5',
        //     'informationPriority' => 'required|integer|min:1|max:5',
        //     'characteristicsPriority' => 'required|integer|min:1|max:5',
        //     'warrantyListPriority' => 'required|integer|min:1|max:5',
        //     'serviceListPriority' => 'required|integer|min:1|max:5',
        // ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'productCategory' => 'required|integer|exists:categories,id',
            'productSubCategory' => 'required|integer|exists:sub_categories,id',
            'minimumQuantity' => 'required|integer|min:1',
            'productImage' => 'image|mimes:jpg,jpeg,png|max:8192|mimes:jpg,jpeg,png|mimetypes:image/jpeg,image/png,image/jpg|max:8192',
            'productDetailImages.*' => 'image|mimes:jpg,jpeg,png|mimetypes:image/jpeg,image/png,image/jpg|max:8192',
            'videoInstruction' => 'nullable|mimes:mp4|max:51200', // 50MB
            'files.*' => 'mimes:pdf|mimetypes:application/pdf|max:8192',
            'product-trixFields' => 'required|array',
            'product-trixFields.*' => ['required', function ($attribute, $value, $fail) {
                if (trim(strip_tags($value)) === '') {
                    $fail("The $attribute field cannot be empty.");
                }
            }],
            'descriptionPriority' => 'required|integer|min:1|max:5',
            'informationPriority' => 'required|integer|min:1|max:5',
            'characteristicsPriority' => 'required|integer|min:1|max:5',
            'warrantylistPriority' => 'required|integer|min:1|max:5',
            'servicelistPriority' => 'required|integer|min:1|max:5',
        ], [
            'files.*.mimetypes' => 'The documents must be a valid PDF file.',
            'files.*.max' => 'The documents must not exceed 8MB.',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return response()->json([
                'message' => $message,
            ], 422);
        }

        $data = array_merge($validator->validated(), $request->allFiles());

        $addFiles = $this->productService->update($data, $product);
        // dd($addFiles);
        if($addFiles->status !== ServiceResponseType::SUCCESS) {
            return response()->json([
                'message' => $addFiles->message,
            ], 422);
        }else{
            return response()->json([
                'message' => 'Product updated successfully',
                'productId' => $product->id,
            ], 201);
        }


        // $this->toasterService->toast($action);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }


    public function getSubcategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)
            ->get(['id', 'name', 'category_id']);


        return response()->json(($subCategories));
    }
}
