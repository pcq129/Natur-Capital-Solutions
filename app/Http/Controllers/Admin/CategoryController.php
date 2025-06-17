<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Http\Requests\Product\Category\CreateCategoryRequest;
use App\Services\ToasterService;

class CategoryController extends Controller
{

    public function __construct(private CategoryService $categoryService, private ToasterService $toasterService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $action = $this->categoryService->fetchCategories($request);
            return $action->data;
        } else {
            return view('Pages.Product.Category.index');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        $action = $this->categoryService->createCategory($data);
        $this->toasterService->toast($action);
        return redirect()->route('categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $action = $this->categoryService->updateCategory($category, $request);
        $this->toasterService->toast($action);
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $action = $this->categoryService->deleteCategory($category);
        $this->toasterService->toast($action);
        return redirect()->route('categories.index');
    }
}
