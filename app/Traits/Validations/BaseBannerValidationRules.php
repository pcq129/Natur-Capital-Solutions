<?php

namespace App\Traits\Validations;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use App\Enums\Status;

trait BaseBannerValidationRules
{
    protected function baseBannerValidationRules(): array
    {
            return [
                // banner_name
                'name'=> 'required|string|max:40',

                // banner_heading and sub_text
                'overlay_heading'=> 'required|string|max:80',
                'overlay_text' => 'string|required',

                // redirection link on banner click
                'banner_link' => 'required|string',

                // to define order of banners
                'priority' => ['integer','required',Rule::unique('App\Models\Banner','priority')->ignore($this->id)],

                // background image for banner
                'image' => 'image|mimes:jpeg,png,jpg,svg,gif|max:2048|dimensions:min_width=800,min_height=600,max_width=1920,max_height=1080',

                'status'=> [new Enum(Status::class)],

                // buttons
                'banner_button_one_text' => 'sometimes|string|nullable|max:20',
                'banner_button_one_action' => 'required_with:banner_button_one_text,string',
                'banner_button_two_text' => 'sometimes|string|nullable|max:20',
                'banner_button_two_action' => 'required_with:banner_button_two_text,string',
                // links
                'banner_link_one_text' => 'sometimes|string|nullable|max:20',
                'banner_link_one_action' => 'required_with:banner_link_one_text,string',
                'banner_link_two_text' => 'sometimes|string|nullable|max:20',
                'banner_link_two_action' => 'required_with:banner_link_two_text,string',
            ];
    }

    protected function baseBannerValidationMessages(): array
    {
        return [
            // Banner name
            'name.required' => 'The banner name is required.',
            'name.string' => 'The banner name must be a valid string.',
            'name.max' => 'The banner name may not be greater than 40 characters.',

            // Overlay heading and text
            'overlay_heading.required' => 'The heading text is required.',
            'overlay_heading.string' => 'The heading must be a valid string.',
            'overlay_heading.max' => 'The heading cannot exceed 20 characters.',
            'overlay_text.required' => 'The sub text is required.',
            'overlay_text.string' => 'The sub text must be a valid string.',

            // Banner link
            'banner_link.required' => 'The banner redirection link is required.',
            'banner_link.string' => 'The banner link must be a valid string.',

            // Priority
            'priority.required' => 'The banner priority is required.',
            'priority.integer' => 'The priority must be a valid integer.',

            // Image
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, svg, gif.',
            'image.max' => 'The image may not be larger than 2MB.',
            'image.dimensions' => 'The image must be between 800x600 and 1920x1080 pixels.',

            // Buttons
            'banner_button_one_text.string' => 'Button one text must be a valid string.',
            'banner_button_one_action.required_with' => 'Button one action is required when its text is provided.',
            'banner_button_one_action.string' => 'Button one action must be a valid string.',

            'banner_button_two_text.string' => 'Button two text must be a valid string.',
            'banner_button_two_action.required_with' => 'Button two action is required when its text is provided.',
            'banner_button_two_action.string' => 'Button two action must be a valid string.',

            // Links
            'banner_link_one_text.string' => 'Link one text must be a valid string.',
            'banner_link_one_action.required_with' => 'Link one action is required when its text is provided.',
            'banner_link_one_action.string' => 'Link one action must be a valid string.',

            'banner_link_two_text.string' => 'Link two text must be a valid string.',
            'banner_link_two_action.required_with' => 'Link two action is required when its text is provided.',
            'banner_link_two_action.string' => 'Link two action must be a valid string.',
        ];
    }
}
