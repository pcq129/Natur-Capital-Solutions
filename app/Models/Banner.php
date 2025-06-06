<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


// Entities relating to this model.
// App\Services\BannerService,
// App\Traits\Validations\BaseBannerValidationRules;
// App\Requests\Banner\*,


class Banner extends Model
{

    use softDeletes;

    public const DELETE_MODAL_ID = '#deleteBannerModal';

    // definations
    protected $fillable = [
        'id',
        'name',
        'image',
        'banner_link',
        'overlay_heading',
        'overlay_text',
        'buttons',
        'links',
        'priority',
        'status',
    ];

    // casting
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'image' => 'string',
            'buttons' => 'json',
            'links' => 'json',
            'overlay_heading' => 'string',
            'overlay_sub_text' => 'string',
            'order' => 'integer',
            'status' => Status::class
        ];
    }
}
