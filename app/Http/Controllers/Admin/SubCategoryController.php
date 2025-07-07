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
use App\Enums\Status;
use App\Constants\SubCategoryConstants as CONSTANTS;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class SubCategoryController extends Controller
{
    public function __construct(private SubCategoryService $subCategoryService, private ToasterService $toasterService) {}

    protected $validationMessage = [
        'name.regex' => CONSTANTS::ERROR_NAME_REGEX,
        'name.string' => CONSTANTS::ERROR_NAME_STRING,
        'name.unique' => CONSTANTS::ERROR_NAME_UNIQUE,
        'category_id.required' => CONSTANTS::ERROR_CATEGORY_ID_REQUIRED,
        'category_id.numeric' => CONSTANTS::ERROR_CATEGORY_ID_NUMERIC,
        'name.min' => CONSTANTS::ERROR_NAME_MIN,
        'name.max' => CONSTANTS::ERROR_NAME_MAX,
        'status.required' => CONSTANTS::ERROR_STATUS_REQUIRED,
        'status.enum' => CONSTANTS::ERROR_STATUS_ENUM,
    ];

    public function validateSubCategoryUpdate(Request $request, SubCategory $subCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:80','min:2','regex:/^[a-zA-Z\s]+$/u', Rule::unique('App\Models\SubCategory','name')->ignore($subCategory)->whereNull('deleted_at')],
            'category_id' => 'required|numeric'
        ], $this->validationMessage);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }

    public function validateSubCategoryStore(Request $request){
        $validator = Validator::make($request->all(), [
                'name' => ['required','string','max:80','min:2','regex:/^[a-zA-Z\s]+$/u', Rule::unique('App\Models\SubCategory','name')->whereNull('deleted_at')],
                'category_id' => ['required','numeric']
            ], $this->validationMessage
        );

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
                if($categories->count()==0){
                    toastr()->error('Cannot add Sub-Categories without Categories.');
                }
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
                'name' => ['required','string','max:80','min:2','regex:/^[a-zA-Z\s]+$/u', Rule::unique('App\Models\SubCategory','name')->whereNull('deleted_at')],
                'category_id' => 'required|numeric'
            ],$this->validationMessage);

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
                'name' => ['required','string','max:80','min:2','regex:/^[a-zA-Z\s]+$/u', Rule::unique('App\Models\SubCategory','name')->ignore($subCategory)->whereNull('deleted_at')],
                'category_id' => 'required|numeric',
                'status' => ['required', new Enum(Status::class)]
            ],$this->validationMessage);

            if ($validator->fails()) {
                $message = $validator->errors()->first();
                toastr()->error($message);
                return redirect()->back();
            }

            $data = $validator->validated();

            $action = $this->subCategoryService->updateSubCategory($subCategory, $data);
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
    public function destroy(Request $request, SubCategory $subCategory)
    {
        try {
            if($request->ajax()){
                $products = $subCategory->products()->get();

                if($products->count()){
                    return response()->json([
                        'message' => $subCategory->name.' have products assigned. Deleting it will delete all related products',
                        'status' => false
                    ], 200);

                }else{
                    return response()->json([
                        'message' => 'Are you sure to delete '.$subCategory->name,
                        'status' => true
                    ], 200);
                }
            }
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
