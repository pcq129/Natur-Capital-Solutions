<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return
            [
                'name' => 'required|string|max:255|unique:products,name,'.$this->route('product')->id,
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
                'isFeatured' => 'sometimes',
            ];
    }

    public function messages(): array
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

    public function attributes(): array
    {
        return [
            'product-trixFields.Information' => 'Product Information',
            'product-trixFields.Characteristics' => 'Product Characteristics',
            'product-trixFields.ServiceList' => 'Product ServiceList',
            'product-trixFields.WarrantyList' => 'Product WarrantyList',
            'product-trixFields.Description' => 'Product Description',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' =>$validator->errors(),
        ], 422));
    }
}
