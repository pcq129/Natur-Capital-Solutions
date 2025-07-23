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
use App\Http\Requests\Admin\Product\CreateProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;

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
        $productId = $request->product ?? '';
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|unique:products,name,' . $productId,
                'productCategory' => 'required|integer|exists:categories,id',
                'productSubCategory' => 'required|integer|exists:sub_categories,id',
                'minimumQuantity' => 'required|integer|min:1',
                'product-trixFields' => 'required|array',
                'product-trixFields.*' => ['required', function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail("The $attribute cannot be empty.");
                    }
                }],
                'descriptionPriority' => 'required|integer|min:1|max:5',
                'informationPriority' => 'required|integer|min:1|max:5',
                'characteristicsPriority' => 'required|integer|min:1|max:5',
                'warrantylistPriority' => 'required|integer|min:1|max:5',
                'servicelistPriority' => 'required|integer|min:1|max:5',
            ],
            $this->getValidationMessages(),
            [
                'product-trixFields.Information' => 'Product Information',
                'product-trixFields.Characteristics' => 'Product Characteristics',
                'product-trixFields.ServiceList' => 'Product ServiceList',
                'product-trixFields.WarrantyList' => 'Product WarrantyList',
                'product-trixFields.Description' => 'Product Description',
                // etc.
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

    public function validateImages(Request $request)
    {
        $required = $request->edit ? '' : 'required';
        $validator = Validator::make(
            $request->all(),
            [
                'productImage' => 'max:8192|mimetypes:image/jpeg,image/png,image/jpg|image|mimes:jpg,jpeg,png|max:8192|' . $required,
                'productDetailImages' => 'array|' . $required,
                'productDetailImages.*' => 'mimetypes:image/jpeg,image/png,image/jpg|image|mimes:jpg,jpeg,png|max:8192|' . $required,
            ],
            [
                'productImage.mimes' => 'Product image must be a file of type: jpg, jpeg, png.',
                'productImage.mimetypes' => 'Product image must be a valid image (JPG, PNG).',
                'productImage.image' => 'Product image must be an actual image.',
                'productImage.max' => 'Product image must not exceed 8MB.',

                'productDetailImages.*.mimes' => 'Each product detail image must be a file of type: jpg, jpeg, png.',
                'productDetailImages.*.mimetypes' => 'Each product detail image must be a valid image (JPG, PNG).',
                'productDetailImages.*.image' => 'Each product detail image must be an image.',
                'productDetailImages.*.max' => 'Each product detail image must not exceed 8MB.',
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::has('sub_categories')->get();
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
            'product-trixFields.*.required' => 'Product :attribute section field is required.',
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

            'servicelistPriority.required' => 'Service list priority is required.',
            'servicelistPriority.integer' => 'Service list priority must be a number.',
            'servicelistPriority.min' => 'Service list priority must be at least 1.',
            'servicelistPriority.max' => 'Service list priority must be at most 5.',
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $action = $this->productService->store($request);
        $this->toasterService->toast($action);
        return response()->json([
            'message' => 'Product created successfully',
            'productId' => $action->data,
        ], 201);
    }



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
    public function update(UpdateProductRequest $request, Product $product)
    {
        if (isset($request->isFeatured)) {
            $product->is_featured = 1;
        } else {
            $product->is_featured = 0;
        }
        if (isset($request->status)) {
            $product->status = Status::ACTIVE;
        } else {
            $product->status = Status::INACTIVE;
        }
        $product->save();
        $data = array_merge($request->validated(), $request->allFiles());
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
