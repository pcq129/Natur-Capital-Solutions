<?php

namespace App\Http\Requests\Product;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->route('product')),
            ],
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
        ];
    }
}
