<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledService extends Model
{
     /**
    * Get the order that owns the ScheduledService
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function order(): BelongsTo
   {
       return $this->belongsTo(Order::class, 'order_id', 'id');
   }
}
