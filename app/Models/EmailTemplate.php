<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Language;
use App\Enums\Status;
use App\Enums\Role;

class EmailTemplate extends Model
{
    public const DELETE_MODAL_ID = 'deleteModal';

    protected $fillable = [
        'template_name',
        'subject',
        'content',
        'language',
        'send_to',
        'status'
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
}
