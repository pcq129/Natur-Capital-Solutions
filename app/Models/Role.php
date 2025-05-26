<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    /**
     * Get the user that owns the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'role');
    }
}
