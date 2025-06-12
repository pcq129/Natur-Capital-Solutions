<?php

namespace App\Models;

use App\Enums\Language;
use App\Enums\Status;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class CmsPage extends Model
{
    use HasTrixRichText, SoftDeletes;

    public const DELETE_MODAL_ID = 'deleteCmsPage';

    protected $fillable = [
        'name',
        // 'content',
        'cmspage-trixFields',
        'language',
        'status'
    ];

    // protected $guarded = [];

    public function casts(): array
    {
        return  [
            'id' => 'integer',
            'name' => 'string',
            // 'content' => CleanHtml::class,
            'language' => Language::class,
            'status' => Status::class
        ];
    }
}
