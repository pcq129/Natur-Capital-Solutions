<?php

namespace App\Http\Requests\ContactDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use App\Enums\ContactType;
use App\Enums\Status;

class UpdateContactDetailRequest extends FormRequest
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

        $rules = [
            'name' => 'string|max:40|required|regex:/^[\pL\s]+$/u|min:3|required',
            'buttonText' => 'string|max:40|required|regex:/^[\pL\s]+$/u|min:3|required',
            'contactType' => [new Enum(ContactType::class), 'required'],
            'actionUrl' => 'url|required',
            'priority' => 'integer|required|unique:contact_details,priority,'.$this->contactDetailId,
            'status' => [new Enum(Status::class)]
        ];

        if ($this->input('contactType') == '1') {
            $rules['contactInput'] = 'email|string';
        } else if ($this->input('contactType') == '2') {
            $rules['contactInput'] = 'regex:/^(\+\d{1,3}[- ]?)?\d{10}$/';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.string' => 'The :attribute must be a valid string.',
            'name.max' => 'The :attribute must not exceed 40 characters.',
            'name.alpha' => 'The :attribute may only contain alphabetic characters.',

            'button.string' => 'The :attribute must be a valid string.',
            'button.max' => 'The :attribute must not exceed 40 characters.',
            'button.alpha' => 'The :attribute may only contain alphabetic characters.',

            'contactType.integer' => 'The :attribute must be an integer.',
            'contactType' => 'The selected :attribute is invalid.',

            'actionUrl.url' => 'The :attribute must be a valid URL.',

            'priority.integer' => 'The :attribute must be a number.',
            'priority.unique' => 'The :attribute is already assigned.',

            'contactInput.email' => 'The :attribute must be a valid email address.',
            'contactInput.string' => 'The :attribute must be a string.',
            'contactInput.regex' => 'The :attribute must be a valid mobile number (e.g., +91XXXXXXXXXX).',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Heading',
            'button' => 'Button label',
            'contactType' => 'Contact type',
            'actionUrl' => 'Redirection URL',
            'priority' => 'Priority',
            'contactInput' => 'Contact',
            'statis' => 'Status'
        ];
    }
}
