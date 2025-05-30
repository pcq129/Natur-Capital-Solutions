<?php

namespace App\Traits;

use Illuminate\Validation\Rules\Enum;
use App\Enums\Status;

trait BaseBannerValidationRules
{
    protected function baseBannerValidationRules(): array
    {
        return [
            // banner_name
            'name'=> 'required|string|max:40',

            // banner_heading and sub_text
            'overlay_heading'=> 'required|string',
            'overlay_text' => 'string|required',

            // redirection link on banner click
            'banner_link' => 'required|string',

            // to define order of banners
            'priority' => 'integer|required',

            // background image for banner
            'image' => 'image|mimes:jpeg,png,jpg,svg,gif|max:2048',

            'status'=> [new Enum(Status::class)],

            // buttons
            'banner_button_one_text' => 'sometimes|string|nullable',
            'banner_button_one_action' => 'required_with:banner_button_one_text,string',
            'banner_button_two_text' => 'sometimes|string|nullable',
            'banner_button_two_action' => 'required_with:banner_button_two_text,string',
            // links
            'banner_link_one_text' => 'sometimes|string|nullable',
            'banner_link_one_action' => 'required_with:banner_link_one_text,string',
            'banner_link_two_text' => 'sometimes|string|nullable',
            'banner_link_two_action' => 'required_with:banner_link_two_text,string',
        ];
    }
}
