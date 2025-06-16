<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Language;
use App\Enums\Status;
use App\Enums\Role;
use Illuminate\Database\Eloquent\SoftDeletes;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplate extends Model
{
    use SoftDeletes, HasTrixRichText;

    // public const DELETE_MODAL_ID = 'deleteModal';
    // removed deleting functionality.

    protected $fillable = [
        'name',
        'subject',
        'content',
        'language',
        'send_to',
        'status',
        'emailtemplate-trixFields'
    ];

    public function casts() : array
    {
        return [
            'template_name' => 'string',
            'subject' => 'string',
            'content' => 'string',
            'language' => Language::class,
            'send_to' => Role::class,
            'status' => Status::class
        ];
    }

    /**
     * Get the user that owns the EmailTemplate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'send_to', 'id');
    }
}
