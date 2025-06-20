<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ToasterService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Status;
use App\Exceptions\Handler;
use App\Constants\CategoryConstants as CONSTANTS;

class CategoryController extends Controller
{

    private $validationMessage = [
        'name.required' => CONSTANTS::ERROR_NAME_REQUIRED,
        'name.string' => CONSTANTS::ERROR_NAME_STRING,
        'name.max' => CONSTANTS::ERROR_NAME_MAX,
        'name.regex' => CONSTANTS::ERROR_NAME_REGEX,
        'name.unique' => CONSTANTS::ERROR_NAME_UNIQUE,
        'status.required' => CONSTANTS::ERROR_STATUS_REQUIRED,
        'status.enum' => CONSTANTS::ERROR_STATUS_ENUM,
        'name.min' => CONSTANTS::ERROR_NAME_MIN
    ];

    public function validateCategoryUpdate(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:80',
                'regex:/^[a-zA-Z\s]+$/u',
                Rule::unique(Category::class, 'name')->ignore($category)->whereNull('deleted_at')
            ],
            'status' => ['required', new Enum(Status::class)],
        ], $this->validationMessage);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }

    public function validateCategoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/u',
                'max:80',
                Rule::unique(Category::class, 'name')->whereNull('deleted_at')
            ]
        ], $this->validationMessage);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }





    public function __construct(private CategoryService $categoryService, private ToasterService $toasterService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $action = $this->categoryService->fetchCategories($request);
                return $action->data;
            } else {
                return view('Pages.Product.Category.index');
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
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:80', 'regex:/^[a-zA-Z\s]+$/u', Rule::unique('categories', 'name')]
            ], [
                'name.unique' => 'Category already exists',
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->first();
                toastr()->error($message);
                return redirect()->back();
            }

            $data = $validator->validated();


            $action = $this->categoryService->createCategory($data);
            $this->toasterService->toast($action);
            return redirect()->route('categories.index');
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
    public function update(Request $request, Category $category)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:80', 'regex:/^[a-zA-Z\s]+$/u',  Rule::unique('App\Models\Category', 'name')->ignore($category)->whereNull('deleted_at')],
                'status' => ['required', new Enum(Status::class)]
            ],  $this->validationMessage);

            if ($validator->fails()) {
                $message = $validator->errors()->first();
                // toastr()->error($message);
                // return redirect()->route('categories.index');
            }

            $data = $validator->validated();

            $action = $this->categoryService->updateCategory($category, $data);
            $this->toasterService->toast($action);
            return redirect()->route('categories.index');
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
    public function destroy(Category $category)
    {
        try {
            $action = $this->categoryService->deleteCategory($category);
            $this->toasterService->toast($action);
            return redirect()->route('categories.index');
        } catch (\Throwable $th) {
            $message = CONSTANTS::DELETE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($th, $message);
            return redirect()->back();
        }
    }
}
