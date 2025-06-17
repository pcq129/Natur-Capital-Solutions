<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SubCategoryService;
// use App\Http\Requests\Product\SubCategory\CreateCategoryRequest;
use App\Services\ToasterService;

class SubCategoryController extends Controller
{
    public function __construct(private SubCategoryService $subCategoryService, private ToasterService $toasterService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $action = $this->subCategoryService->fetchCategories($request);
            return $action->data;
        } else {
            return view('Pages.Product.SubCategory.index');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubCategoryRequest $request)
    {
        $data = $request->validated();
        $action = $this->subCategoryService->createSubCategory($data);
        $this->toasterService->toast($action);
        return redirect()->route('sub-categories.index');
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
    public function update(Request $request, SubCategory $category)
    {
        $action = $this->subCategoryService->updateSubCategory($category, $request);
        $this->toasterService->toast($action);
        return redirect()->route('sub-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $category)
    {
        $action = $this->subCategoryService->deleteSubCategory($category);
        $this->toasterService->toast($action);
        return redirect()->route('sub-categories.index');
    }
}
