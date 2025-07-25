<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use SoftDeletes;

    public $fillable =[
        'product_id',
        'user_id',
        'message',
        'quantity',
        'country'
    ];

    public $casts = [
        'product_id' => 'integer',
        'user_id' => 'integer',
        'quantity' => 'integer',
        'country' => 'integer',
        'message' => 'string',
        'response' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the country associated with the Enquiry
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
