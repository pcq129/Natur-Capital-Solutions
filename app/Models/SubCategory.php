<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\Status;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use SoftDeletes;

    /**
     * Get all of the products for the SubCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'id');
    }

    /**
     * Get the category that owns the SubCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    protected $fillable = [
        'name',
        'status',
        'category_id'
    ];

    protected function casts(): array
    {
        return [
            'id'=>'integer',
            'name'=>'string',
            'status' => Status::class,
            'category_id' => 'integer'
        ];
    }


    protected static function booted()
    {
        static::deleting(function ($subCategory) {
            $subCategory->products()->delete();
        });
    }
}
