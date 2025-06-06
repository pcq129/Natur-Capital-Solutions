<?php

namespace App\Traits\Validations;

use App\Enums\Language;
use Illuminate\Validation\Rules\Enum;

trait BaseCmsPageValidationRules
{
    public function BaseCmsPageValidationRules(): array
    {
        return [
            "name" => 'required|string',
            "language" => ['required', new Enum(Language::class)],
            "cmspage-trixFields" => 'required|array',
            "attachment-cmspage-trixFields" => 'array'
        ];
    }

    public function BaseCmsPageValidationMessages(): array
    {
        return [
            'name.required' => 'The page name is required.',
            'name.string' => 'The page name must be a valid string.',

            'language.required' => 'Please select a language.',
            'language.enum' => 'The selected language is invalid.',

            'cmspage-trixFields.required' => 'The CMS page content is required.',
            'cmspage-trixFields.array' => 'The content must be submitted as an array.',

            'attachment-cmspage-trixFields.array' => 'Attachments must be submitted as an array.',
        ];
    }
}
