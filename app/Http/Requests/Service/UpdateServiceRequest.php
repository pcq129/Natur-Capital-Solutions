<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'description' => 'string|max:255',
            'serviceIcon' => 'mimetypes:image/jpg,image/jpeg,image/png|max:2500',
            'status' => 'boolean|sometimes'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name must not exceed 80 characters.',

            'description.string' => 'The description must be a valid string.',
            'description.max' => 'The description must not exceed 255 characters.',

            'serviceIcon.required' => 'Please upload a service icon.',
            'serviceIcon.mimetypes' => 'The service icon must be a JPG or PNG image (jpg, jpeg, png).',
            'serviceIcon.max' => 'The service icon size must not exceed 2.5MB.',

        ];
    }

    public function attributes(): array
    {
        $attributes = [
            'name' => 'Service Name',
            'description' => 'Service Description',
            'serviceIcon' => 'Service Icon',
        ];

        return $attributes;
    }
}
