<?php

namespace App\Http\Requests\CmsPage;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Validations\BaseCmsPageValidationRules;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Status;

class UpdateCmsPageRequest extends FormRequest
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
        return array_merge(
            [
                "status" => ['required', new Enum(Status::class)]
            ],
            $this->BaseCmsPageValidationRules()
        );
    }

    public function messages(): array
    {
        return array_merge(
            [
                "status.required" => "Please specify a state for CMS page",
                "status.enum" => "Invalid status selected"
            ],
            $this->BaseCmsPageValidationMessages()
        );
    }
}
