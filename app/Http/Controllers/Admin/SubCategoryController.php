<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SubCategoryService;
// use App\Http\Requests\Product\SubCategory\CreateCategoryRequest;
use App\Services\ToasterService;
use App\Http\Requests\Product\SubCategory\createSubCategoryRequest;
use App\Http\Requests\Product\SubCategory\updateSubCategoryRequest;
use App\Models\Category;
use App\Exceptions\Handler;
use App\Constants\SubCategoryConstants as CONSTANTS;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubCategoryController extends Controller
{
    public function __construct(private SubCategoryService $subCategoryService, private ToasterService $toasterService) {}


    public function validateSubCategoryUpdate(Request $request, SubCategory $subCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:80', Rule::unique('App\Models\SubCategory','name')->ignore($subCategory)->whereNull('deleted_at')],
            'category_id' => 'required|numeric'
        ], [
            'name.unique' => 'Sub-Category with same name already exists',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }

    public function validateSubCategoryStore(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => [
                'name' => ['required','string','max:80', Rule::unique('App\Models\SubCategory','name')->whereNull('deleted_at')],
                'category_id' => ['required','numeric']
            ], [
            'name.unique' => 'Sub-Category with same name already exists',
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $categories = Category::all('id','name');
            if ($request->ajax()) {
                $action = $this->subCategoryService->fetchSubCategories($request);
                return $action->data;
            } else {
                return view('Pages.Product.SubCategory.index', ['categories' => $categories]);
            }
        } catch (\Throwable $th) {
            $message = CONSTANTS::FETCH_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($th, $message);
            return redirect()->back();
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'name' => ['required','string','max:80', Rule::unique('App\Models\SubCategory','name')->whereNull('deleted_at')],
                'category_id' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->first();
                toastr()->error($message);
                return redirect()->back();
            }

            $data = $validator->validated();

            $action = $this->subCategoryService->createSubCategory($data);
            $this->toasterService->toast($action);
            return redirect()->route('sub-categories.index');
        } catch (\Throwable $th) {
            $message = CONSTANTS::STORE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($th, $message);
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        try {
            $validator = Validator::make($request->all(),[
                'name' => ['required','string','max:80', Rule::unique('App\Models\SubCategory','name')->ignore($subCategory)->whereNull('deleted_at')],
                'category_id' => 'required|numeric'
            ],[
                'name.unique' => CONSTANTS::UPDATE_ALREADY_EXISTS
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->first();
                toastr()->error($message);
                return redirect()->back();
            }

            $data = $validator->validated();

            $action = $this->subCategoryService->updateSubCategory($subCategory, $request);
            $this->toasterService->toast($action);
            return redirect()->route('sub-categories.index');
        } catch (\Throwable $th) {
            $message = CONSTANTS::UPDATE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($th, $message);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        try {
            $action = $this->subCategoryService->deleteSubCategory($subCategory);
            $this->toasterService->toast($action);
            return redirect()->route('sub-categories.index');
        } catch (\Throwable $th) {
            $message = CONSTANTS::DELETE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($th, $message);
            return redirect()->back();
        }
    }
}
