<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ResourceType;

class Resource extends Model
{

    use HasFactory, SoftDeletes;


    public function resourceable()
    {
        return $this->morphTo();
    }


    protected $fillable = [
        'resource_type',
        'priority',
        'resource',
    ];

    protected $casts = [
        'resource_type' => ResourceType::class,
        'resource' => 'string',
        'priority' => 'integer',
        'id' => 'integer',
    ];
}
