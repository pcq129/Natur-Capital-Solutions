<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\SubService;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    public function resources(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

    /**
     * Get all of the SubServices for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function SubServices(): HasMany
    {
        return $this->hasMany(SubServices::class, 'foreign_key', 'local_key');
    }

    protected $casts = [
        'id' => 'integer',
        'name'=> 'string',
        'description' => 'string',
        'icon' => 'string',
        'status' => Status::class
    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'icon'
    ];
}
