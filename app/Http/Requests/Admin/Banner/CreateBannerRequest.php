<?php

namespace App\Http\Requests\Admin\Banner;

use App\Enums\Role;
use App\Traits\Validations\BaseBannerValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateBannerRequest extends FormRequest
{
    use BaseBannerValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::check()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            $this->baseBannerValidationRules(),
            [
                'image' => 'required|dimensions:min_width=800,min_height=600,max_width=1920,max_height=1080'
            ]
        );
    }


    public function messages(): array
    {
        return [
            'image.required' => 'Banner image is required',
            'image.dimensions' => 'The image must be between 800x600 and 1920x1080 pixels'
        ];
    }



}
