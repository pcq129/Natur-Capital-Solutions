<?php

namespace App\Http\Requests\Admin\EmailTemplate;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Validations\BaseEmailTemplateValidationRules;
use App\Enums\Status;
use Illuminate\Validation\Rules\Enum;


class UpdateEmailTemplateRequest extends FormRequest
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
                'status' => ['required',new Enum(Status::class)]
                // custom validations here (in additon with base validations)
            ]
        );
    }

    public function messages(): array
    {
        return array_merge(
            $this->BaseEmailTemplateValidationMessages(),
            [
                'status.required' => 'Please specify current status for email',
                'status.enum' => 'Invalid status'
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
