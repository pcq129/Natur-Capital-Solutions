<?php

namespace App\Traits;

use App\Models\Resource;

trait HasResources
{
    public function resources()
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }
}
