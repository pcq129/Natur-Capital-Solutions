<?php

namespace App\Models;

use App\Enums\ContactType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactDetail extends Model
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'integer',
        'name'=>'string',
        'button_text'=> 'string',
        'contact' => 'string',
        'contact_type' => ContactType::class,
        'status' => Status::class,
        'action_url' => 'string',
        'priority'=>'integer',
    ];

    protected $fillable = [
        'name',
        'button_text',
        'contact',
        'contact_type',
        'status',
        'action_url',
        'priority',
    ];
}
