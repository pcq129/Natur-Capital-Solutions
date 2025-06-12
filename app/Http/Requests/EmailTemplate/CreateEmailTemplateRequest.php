<?php

namespace App\Http\Requests\EmailTemplate;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Validations\BaseEmailTemplateValidationRules;

class CreateEmailTemplateRequest extends FormRequest
{

    use BaseEmailTemplateValidationRules;

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
            $this->baseEmailTemplateValidationRules(),
            [
                // custom validations here (in additon with base validations)
            ]
        );
    }

    public function messages(): array
    {
        return array_merge(
            $this->BaseEmailTemplateValidationMessages(),
            [
                // custom messages for custom validations.
            ]
        );
    }

    public function attributes(): array
    {
        return array_merge(
        $this->BaseEmailTemplateValidationAttributes(),
            [
                // convert attributes to human readable form
            ]
        );
    }
}
