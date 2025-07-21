<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\SubService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class Service extends Model
{
    use SoftDeletes, HasTrixRichText;

    // public function resources(): MorphMany
    // {
    //     return $this->morphMany(Resource::class, 'resourceable');
    // }

    /**
     * Get all of the serviceSections for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceSections(): HasMany
    {
        return $this->hasMany(ServiceSection::class, 'service_id', 'id');
    }

    protected $casts = [
        'id' => 'integer',
        'name'=> 'string',
        'description' => 'string',
        'icon' => 'string',
        'status' => Status::class,
    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'icon',
        'status',
    ];

    protected static function booted()
    {
        static::deleting(function ($service) {
            $service->serviceSections()->delete();
        });
    }
}
