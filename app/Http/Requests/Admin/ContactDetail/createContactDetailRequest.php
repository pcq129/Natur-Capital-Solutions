<?php

namespace App\Http\Requests\Admin\ContactDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use App\Enums\ContactType;

class createContactDetailRequest extends FormRequest
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
        // dd($this);
        // name: test
        // buttonText: test
        // contactType: 2
        // contactInput: test@gadf
        // action_url: sdfsdffdfsff
        // priority: 1

        $rules = [
            'name' => 'string|max:40|required|regex:/^[\pL\s]+$/u|min:3|required',
            'buttonText' => 'string|max:40|required|regex:/^[\pL\s]+$/u|min:3|required',
            'contactType' => [new Enum(ContactType::class), 'required'],
            'actionUrl' => 'url|required',
            'priority' => ['integer','required', Rule::unique('contact_details','priority')->ignore($this->route('contact-detail'))->whereNull('deleted_at')]
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
        ];
    }
}
