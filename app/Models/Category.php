<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Get all of the products for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    /**
     * Get all of the sub_categories for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sub_categories(): HasMany
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }

    protected $fillable = [
        'name'
    ];

    protected function casts(): array
    {
        return [
            'id'=>'integer',
            'name'=>'string',
        ];
    }
}
