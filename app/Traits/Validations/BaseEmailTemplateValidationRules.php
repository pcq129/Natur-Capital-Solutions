<?php

namespace App\Traits\Validations;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use App\Enums\Language;
use App\Enums\Role;


trait BaseEmailTemplateValidationRules
{
    public function BaseEmailTemplateValidationRules(){
        return [
            'name'=> ['required','string','max:40', Rule::unique('App\Models\EmailTemplate','name')->ignore($this->route('email_template'))->whereNull('deleted_at')],
            'subject' => ['required', 'string', 'max:240'],
            'language' => ['required', new Enum(Language::class)],
            'emailtemplate-trixFields.EmailTemplateContent' => ['string','required','min:20'],
            'role' => ['required', new Enum(Role::class)],
        ];
    }

    public function BaseEmailTemplateValidationMessages(){
        return [
            'name.required' => 'The template name is required.',
            'name.string' => 'The template name must be a valid string.',
            'name.max' => 'The template name must not be longer than 40 characters.',

            'subject.required' => 'The subject is required.',
            'subject.string' => 'The subject must be a valid string.',
            'subject.max' => 'The subject must not be longer than 240 characters.',

            'language.required' => 'The language field is required.',
            'language.enum' => 'The selected language is invalid.',

            'emailtemplate-trixFields.EmailTemplateContent.required' => 'The email content cannot be empty.',
            'emailtemplate-trixFields.EmailTemplateContent.string' => 'The email content must be a valid string.',
            'emailtemplate-trixFields.EmailTemplateContent.min' => 'The email content must be at least 20 characters long.',
        ];
    }

    public function BaseEmailTemplateValidationAttributes(){
        return [
            'emailtemplate-trixFields.EmailTemplateContent' => 'Email Content'
        ];
    }
}
