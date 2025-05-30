<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mews\Purifier\Casts\CleanHtml;

// Other entities relating to this model

// App\Services\Banner,
// App\Traits\BaseBannerValidationRules,
// App\Requests\Banner\*,

class Banner extends Model
{

    // Follow Naming Conventions:
    // "Medium Models, fat services, Skinny Controllers":
    // Mass Assignment Protection ($fillable or $guarded):
    // Define Relationships:
    // Accessors and Mutators:
    // Attribute Casting ($casts):
    // Query Scopes:
    // Soft Deleting:
    // Validation (Use Form Requests):
    // Hide Sensitive Attributes ($hidden):

    use softDeletes;

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
