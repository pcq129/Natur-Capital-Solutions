<?php

namespace App\Http\Requests\Banner;

use App\Enums\Role;
use App\Traits\BaseBannerValidationRules;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;

class StoreBannerRequest extends FormRequest
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
                'image' => 'required'
            ]
        );
    }
}
