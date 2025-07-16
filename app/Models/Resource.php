<?php

namespace App\Models;

use App\Enums\FileType;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ResourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'resourceable_id',
        'resourceable_type',
    ];

    protected $casts = [
        'resource_type' => FileType::class,
        'resource' => 'string',
        'priority' => 'integer',
        'id' => 'integer',
    ];
}
