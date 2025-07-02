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

    public function validateText(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|unique:products,name',
                'productCategory' => 'required|integer|exists:categories,id',
                'productSubCategory' => 'required|integer|exists:sub_categories,id',
                'minimumQuantity' => 'required|integer|min:1',
                'product-trixFields' => 'required|array',
                'product-trixFields.*' => ['required', function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail("The $attribute field cannot be empty.");
                    }
                }],
                'descriptionPriority' => 'required|integer|min:1|max:5',
                'informationPriority' => 'required|integer|min:1|max:5',
                'characteristicsPriority' => 'required|integer|min:1|max:5',
                'warrantyListPriority' => 'required|integer|min:1|max:5',
                'serviceListPriority' => 'required|integer|min:1|max:5',
            ],
            $this->getValidationMessages()
        );

        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
                'success' => false

            ], 201);
        } else {
            return response()->json([
                'success' => true
            ], 201);
        }
    }

    public function validateImages(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'productImage' => 'max:8192|mimetypes:image/jpeg,image/png,image/jpg|image|mimes:jpg,jpeg,png|max:8192',
                'productDetailImages.*' => 'mimetypes:image/jpeg,image/png,image/jpg|image|mimes:jpg,jpeg,png|max:8192',
            ],
            [
                'productDetailImages.*.mimetypes' => 'Invalid/Corrupt product detail Images.',
                'productImage.mimetypes' => 'Invalid/Corrupt product image.',
            ]
        );

        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
                'success' => false

            ], 201);
        } else {
            return response()->json([
                'success' => true
            ], 201);
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

    public function getValidationMessages()
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a valid string.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'name.unique' => 'A product with this name already exists.',

            'productCategory.required' => 'Please select a category.',
            'productCategory.integer' => 'Category ID must be a valid number.',
            'productCategory.exists' => 'Selected category does not exist.',

            'productSubCategory.required' => 'Please select a subcategory.',
            'productSubCategory.integer' => 'Subcategory ID must be a valid number.',
            'productSubCategory.exists' => 'Selected subcategory does not exist.',

            'minimumQuantity.required' => 'Minimum quantity is required.',
            'minimumQuantity.integer' => 'Minimum quantity must be a number.',
            'minimumQuantity.min' => 'Minimum quantity must be at least 1.',

            'productImage.mimes' => 'Product image must be a file of type: jpg, jpeg, png.',
            'productImage.mimetypes' => 'Product image must be a valid image (JPG, PNG).',
            'productImage.image' => 'Product image must be an actual image.',
            'productImage.max' => 'Product image must not exceed 8MB.',

            'productDetailImages.*.mimes' => 'Each product detail image must be a file of type: jpg, jpeg, png.',
            'productDetailImages.*.mimetypes' => 'Each product detail image must be a valid image (JPG, PNG).',
            'productDetailImages.*.image' => 'Each product detail image must be an image.',
            'productDetailImages.*.max' => 'Each product detail image must not exceed 8MB.',

            'videoInstruction.mimes' => 'Video instruction must be an MP4 file.',
            'videoInstruction.mimetypes' => 'Video instruction must be a valid MP4 video.',
            'videoInstruction.max' => 'Video instruction must not exceed 50MB.',

            'files.*.required' => 'Please upload all required documents.',
            'files.*.mimes' => 'Documents must be PDF files.',
            'files.*.mimetypes' => 'Documents must be valid PDF files.',
            'files.*.max' => 'Each document must not exceed 8MB.',

            'product-trixFields.required' => 'Product details are required.',
            'product-trixFields.array' => 'Product details must be in array format.',
            'product-trixFields.*.required' => 'Each product detail field is required.',
            // The custom closure handles "empty" HTML, so no message is needed here.

            'descriptionPriority.required' => 'Description priority is required.',
            'descriptionPriority.integer' => 'Description priority must be a number.',
            'descriptionPriority.min' => 'Description priority must be at least 1.',
            'descriptionPriority.max' => 'Description priority must be at most 5.',

            'informationPriority.required' => 'Information priority is required.',
            'informationPriority.integer' => 'Information priority must be a number.',
            'informationPriority.min' => 'Information priority must be at least 1.',
            'informationPriority.max' => 'Information priority must be at most 5.',

            'characteristicsPriority.required' => 'Characteristics priority is required.',
            'characteristicsPriority.integer' => 'Characteristics priority must be a number.',
            'characteristicsPriority.min' => 'Characteristics priority must be at least 1.',
            'characteristicsPriority.max' => 'Characteristics priority must be at most 5.',

            'warrantyListPriority.required' => 'Warranty list priority is required.',
            'warrantyListPriority.integer' => 'Warranty list priority must be a number.',
            'warrantyListPriority.min' => 'Warranty list priority must be at least 1.',
            'warrantyListPriority.max' => 'Warranty list priority must be at most 5.',

            'serviceListPriority.required' => 'Service list priority is required.',
            'serviceListPriority.integer' => 'Service list priority must be a number.',
            'serviceListPriority.min' => 'Service list priority must be at least 1.',
            'serviceListPriority.max' => 'Service list priority must be at most 5.',
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|unique:products,name',
                'productCategory' => 'required|integer|exists:categories,id',
                'productSubCategory' => 'required|integer|exists:sub_categories,id',
                'minimumQuantity' => 'required|integer|min:1',
                'productImage' => 'max:8192|mimetypes:image/jpeg,image/png,image/jpg|image|mimes:jpg,jpeg,png|max:8192',
                'productDetailImages.*' => 'mimetypes:image/jpeg,image/png,image/jpg|image|mimes:jpg,jpeg,png|max:8192',
                'videoInstruction' => 'nullable|mimetypes:video/mp4|mimes:mp4|max:51200', // 50MB
                'files.*' => 'required|mimes:pdf|mimetypes:application/pdf|max:8192',
                'product-trixFields' => 'required|array',
                'product-trixFields.*' => ['required', function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail("The $attribute field cannot be empty.");
                    }
                }],
                'descriptionPriority' => 'required|integer|min:1|max:5',
                'informationPriority' => 'required|integer|min:1|max:5',
                'characteristicsPriority' => 'required|integer|min:1|max:5',
                'warrantyListPriority' => 'required|integer|min:1|max:5',
                'serviceListPriority' => 'required|integer|min:1|max:5',
            ],
            $this->getValidationMessages()
        );

        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 422);
        }
        $data = $validator->validated();
        // dd($data);

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
                'file_name' => $document->getClientOriginalName(),
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

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
                'productCategory' => 'required|integer|exists:categories,id',
                'productSubCategory' => 'required|integer|exists:sub_categories,id',
                'minimumQuantity' => 'required|integer|min:1',
                'productImage' => 'image|max:8192|mimes:jpg,jpeg,png|mimetypes:image/jpeg,image/png,image/jpg|max:8192',
                'productDetailImages.*' => 'image|mimes:jpg,jpeg,png|mimetypes:image/jpeg,image/png,image/jpg|max:8192',
                'videoInstruction' => 'nullable|mimes:mp4|mimetypes:video/mp4|max:51200', // 50MB
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
            ],
            $this->getValidationMessages()
        );

        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 422);
        }

        $data = array_merge($validator->validated(), $request->allFiles());

        $addFiles = $this->productService->update($data, $product);
        // dd($addFiles);
        if ($addFiles->status !== ServiceResponseType::SUCCESS) {
            return response()->json([
                'message' => $addFiles->message,
            ], 422);
        } else {
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
        $action = $this->productService->destroy($product);
        $this->toasterService->toast($action);
        return redirect()->route('products.index');
    }


    public function getSubcategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)
            ->get(['id', 'name', 'category_id']);


        return response()->json(($subCategories));
    }
}
