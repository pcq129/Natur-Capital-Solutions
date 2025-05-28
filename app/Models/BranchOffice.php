<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchOffice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'office',
        'email',
        'mobile',
        'status',
        'location'
    ];

    public function casts(): array {
        return [
            'name' => 'string',
            'office' => 'string',
            'email' => 'string',
            'mobile' => 'string',
            'status' => Status::class,
        ];
    }
}
