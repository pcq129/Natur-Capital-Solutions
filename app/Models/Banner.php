<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Services\Banner;

class Banner extends Model
{

    // Follow Naming Conventions:
    // "Medium Models, fat services, Skinny Controllers":
    // Mass Assignment Protection ($fillable or $guarded):
    // Define Relationships:
    // Accessors and Mutators:
    // Attribute Casting ($casts):
    // Query Scopes:
    // Soft Deleting:
    // Validation (Use Form Requests):
    // Hide Sensitive Attributes ($hidden):

    use softDeletes;

    // definations
    protected $fillable = [
        'id',
        'image',
        'buttons',
        'links'
    ];

    // validation rules
    public $validationRules = [
        'links' => 'required|array|size:2',
        'image' => 'required|image|mimes:jpeg,png,jpg,svg,gif|max:2048',
        'buttons' => 'required|array|size:2',
    ];

    // casting
    protected function casts(): array
    {
        return [
            'id' => 'numeric',
            'buttons' => 'array',
            'links' => 'array'
        ];
    }
}
