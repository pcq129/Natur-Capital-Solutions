<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/*
   this model is required to aggregate individual product orders
   and display/document them to the end user in a more simpler
   format.
*/

class Invoice extends Model
{
    /**
     * Get all of the orders for the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'invoice_id', 'id');
    }
}
