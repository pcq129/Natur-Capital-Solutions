<?php

namespace App\Traits\Validations;

use App\Enums\Status;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

trait BaseBranchOfficeValidationRules
{
    public function baseBranchOfficeValidationRules(): array
    {
        return [
            'name' => ['required','string','max:80',Rule::unique('App\Models\BranchOffice','name')->ignore($this->id)],
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:80',
            'mobile' => 'numeric|max_digits:20',
            'status' => [new Enum(Status::class)],
            'location' => 'string|max:30'
        ];
    }

    public function baseBranchOfficeValidationMessages(): array
    {
        return [
            // Name
            'name.required' => 'The branch name is required.',
            'name.string' => 'The branch name must be a valid string.',
            'name.max' => 'The branch name must not exceed 80 characters.',
            'name.unique' => 'This branch name is already in use. Please choose a different one.',

            // Address
            'address.required' => 'The address is required.',
            'address.string' => 'The address must be a valid string.',
            'address.max' => 'The address must not exceed 255 characters.',

            // Email
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email address must not exceed 80 characters.',

            // Mobile
            'mobile.numeric' => 'The mobile number must be a valid number.',
            'mobile.max_digits' => 'The mobile number must not exceed 20 digits.',

            // Status (Enum)
            'status' => 'The selected status is invalid.',

            // Location
            'location.string' => 'The location must be a valid string.',
            'location.max' => 'The location must not exceed 30 characters.',
        ];
    }
}
