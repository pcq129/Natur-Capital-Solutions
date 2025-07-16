<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ServiceSection extends Model
{
    use HasTrixRichText, SoftDeletes;

    protected $fillable = [
        'content',
        'heading',
        'priority',
        'service_id',
        // 'servicesection-trixFields',
        // 'attachment-servicesection-trixFields'
    ];

    protected $casts = [
        'content'=>'string',
        'heading'=>'string',
        'priority'=>'integer',
        'service_id'=>'integer'
    ];

    /**
     * Get the service that owns the ServiceSection
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function resources(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

}
