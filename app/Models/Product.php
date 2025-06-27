<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Enums\Status;
use App\Traits\HasResources;
use Illuminate\Database\Eloquent\SoftDeletes;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes, HasTrixRichText, HasResources;

    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Get the sub_category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }

    /**
     * Get all of the Sections for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Sections(): HasMany
    {
        return $this->hasMany(ProductSection::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductFiles for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductFiles(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'product_id', 'id');
    }

    protected $fillable = [
        'name',
        'category_id',
        'sub_category_id',
        'minimum_quantity',
        'is_featured',
        'status',
        'product-trixFields'
    ];

    protected $casts = [
        'name' => 'string',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'minimum_quantity' => 'integer',
        'is_featured' => 'boolean',
        'status' => Status::class
    ];
}
