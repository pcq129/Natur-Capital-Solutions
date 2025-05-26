<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    /**
     * Get all of the states for the Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class, 'country_id', 'id');
    }

    /**
     * Get all of the cities for the Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'country_id', 'id');
    }
}
