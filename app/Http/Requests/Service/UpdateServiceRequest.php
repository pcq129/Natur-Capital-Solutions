<?php

namespace App\Http\Requests\Service;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateServiceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serviceIcon' => 'mimetypes:image/jpg,image/jpeg,image/png|max:2500',
            'serviceName' => 'required|string|max:80|unique:services,name,' . $this->route('service')->id,
            'removedSections' => 'sometimes|string',
            'deletedFiles' => 'sometimes|string',
            'addedFiles' => 'sometimes|string',
            'serviceDescription' => 'string|max:255',
            'currentServiceSectionName' => 'sometimes|array|required',
            'currentServiceSectionName.*' => 'required|string|max:80',
            'currentServiceSection-trixFields' =>'sometimes|array|required',
            'currentServicesection-trixFields.*' => 'required|string|min:1',
            'status' => 'nullable|integer',
            'sectionName' => 'required_without:currentServiceSectionName|array',
            'servicesection-trixFields' => 'required_without:currentServiceSection-trixFields|array',
            'attachment-servicesection-trixFields' => 'required_without:currentServiceSection-trixFields|array',
            'servicesection-trixFields.*' => 'required_with:servicesection-trixFields|string|min:1',
            'sectionName.*' => 'required_with:sectionName|string|max:80',
            'attachment-servicesection-trixFields.*' => 'required_with:attachment-servicesection-trixFields|string',
        ];
    }

    public function messages(): array
    {
        return [
            'serviceName.required' => 'The service name is required.',
            'serviceName.string' => 'The service name must be a valid string.',
            'serviceName.max' => 'The service name may not be greater than 80 characters.',

            'serviceDescription.string' => 'The service description must be a valid string.',
            'serviceDescription.max' => 'The service description may not be greater than 255 characters.',

            'sectionName.required' => 'At least one section is required.',
            'sectionName.array' => 'The section data must be sent as an array.',

            'servicesection-trixFields.required' => 'The section content is required.',
            'servicesection-trixFields.array' => 'The section content must be in array format.',

            'attachment-servicesection-trixFields.required' => 'Trix field attachments are missing.',
            'attachment-servicesection-trixFields.array' => 'Trix attachments must be in array format.',

            'serviceIcon.required' => 'Please upload a service icon.',
            'serviceIcon.mimetypes' => 'The service icon must be a file of type: jpg, jpeg, png.',
            'serviceIcon.max' => 'The service icon must not be larger than 2.5MB.',
        ];
    }

    public function attributes(): array
    {
        $attributes = [
            'serviceName' => 'Service Name',
            'serviceDescription' => 'Service Description',
            'serviceIcon' => 'Service Icon',
            'sectionName.*' => 'Section Name',
        ];

        return $attributes;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' =>$validator->errors(),
        ], 400));
    }
}
