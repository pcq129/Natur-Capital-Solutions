<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    /**
     * Get the country associated with the Enquiry
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
}
