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

    public function addFiles(Request $request, $product ){
        dd($request);
        $product = Product::findOrFail($request->product);
        dd($product, $request->file('productImage'));
    }

    public function addImages(Request $request, $product)
    {

        $productImage = $this->fileService->uploadImage(
            $request->file('productImage'),
            ProductConstants::PRODUCT_IMAGE_PATH
        );

        ProductFile::create([
            'product_id' => $product,
            'file_name' =>  $request->file('productImage')->getClientOriginalExtension(),
            'file_path' => $productImage->data,
            'file_type' => FileType::MAIN_IMAGE->value,
        ]);

        foreach ($request->file('productDetailImages') as $image) {
            $productDetailImage = $this->fileService->uploadImage(
                $image,
                ProductConstants::PRODUCT_IMAGE_PATH
            );

            ProductFile::create([
                'product_id' => $product,
                'file_name' => $image->getClientOriginalExtension(),
                'file_path' => $productDetailImage->data,
                'file_type' => FileType::IMAGE->value,
            ]);
        }
        toastr()->success('Product images added successfully');
        return view('Pages.Product.add-files', ['productId' => $product]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('Pages.Product.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only(
            "isFeatured" ,
            "name",
            "productCategory",
            "productSubCategory",
            "minimumQuantity",
            "product-trixFields",
            "descriptionPriority",
            "informationPriority",
            "characteristicsPriority",
            "warrantyListPriority",
            "serviceListPriority",
        );
        $action= $this->productService->store($data);
        $this->toasterService->toast($action);
        return redirect()->route('products.add-files-page', ['product' => $action->data]);
    }

    /**
     * Show the form for adding files to a product.
     */
    public function addImagesForm(Request $request)
    {
        $productId = $request->query('product');
        return view('Pages.Product.add-images', compact('productId'));
    }


       /**
     * Show the form for adding files to a product.
     */
    public function addFilesForm(Request $request)
    {
        $productId = $request->query('product');
        return view('Pages.Product.add-files', compact('productId'));
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
    public function edit(Product $product)
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


    public function getSubcategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)
        ->get(['id', 'name', 'category_id']);


        return response()->json(($subCategories));
    }
}
