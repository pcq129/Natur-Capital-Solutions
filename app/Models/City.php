<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    /**
     * Get the state that owns the City
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

   /**
    * Get the country that owns the City
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function country(): BelongsTo
   {
       return $this->belongsTo(Country::class, 'country_id', 'id');
   }
}
