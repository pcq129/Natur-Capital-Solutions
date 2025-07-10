<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Service extends Model
{
    public function resources(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

    // /**
    //  * Get all of the SubServices for the Service
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function SubServices(): HasMany
    // {
    //     return $this->hasMany(SubServices::class, 'foreign_key', 'local_key');
    // }

    protected $casts = [
        'id' => 'integer',
        'name'=> 'string',
        'description' => 'string',
        'icon' => 'string'
    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'icon'
    ];
}
