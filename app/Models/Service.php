<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Service extends Model
{
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
