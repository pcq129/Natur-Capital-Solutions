<?php

namespace App\Traits;

trait BaseBannerRules
{
    protected function baseBannerValidationRules(): array
    {
        return [
            'links' => 'required|array|size:2',
            'buttons' => 'required|array|size:2',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg,gif|max:2048',
        ];
    }
}
