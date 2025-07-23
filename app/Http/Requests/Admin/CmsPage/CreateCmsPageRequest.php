<?php

namespace App\Http\Requests\Admin\CmsPage;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Validations\BaseCmsPageValidationRules;

class CreateCmsPageRequest extends FormRequest
{

    use BaseCmsPageValidationRules;

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
        return $this->BaseCmsPageValidationRules();
    }

    public function messages(): array
    {
        return $this->BaseCmsPageValidationMessages();
    }
}
