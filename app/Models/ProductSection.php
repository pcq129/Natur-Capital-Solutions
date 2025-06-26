<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSection extends Model
{
    protected $fillable = [
        'product_id',
        'content',
        'priority',
        'name',
        'type',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'content' => 'string',
        'priority' => 'integer',
        'name' => 'string',
        'type' => 'string', // Assuming type is a string, adjust if it's an enum or other type
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
