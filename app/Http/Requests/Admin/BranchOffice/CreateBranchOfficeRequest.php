<?php

namespace App\Http\Requests\Admin\BranchOffice;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Validations\BaseBranchOfficeValidationRules;
use Illuminate\Support\Facades\Auth;

class CreateBranchOfficeRequest extends FormRequest
{

    use BaseBranchOfficeValidationRules;

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
        return $this->baseBranchOfficeValidationRules();
    }


    public function messages(): array
    {
        return $this->baseBranchOfficeValidationMessages();
    }
}
