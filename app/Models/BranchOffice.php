<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Status;


/*
// Entities relating to this model.
//
// App\Services\BranchOfficeService,
// App\Traits\Validations\BaseBannerValidationRules;
// App\Requests\Banner\*,
*/

class BranchOffice extends Model
{
    use SoftDeletes;

    public const BRANCH_DELETE_ID = 'branchDeleteModalId';

    protected $fillable = [
        'name',
        'address',
        'email',
        'mobile',
        'status',
        'location'
    ];

    public function casts(): array
    {
        return [
            'name' => 'string',
            'office' => 'string',
            'email' => 'string',
            'mobile' => 'string',
            'status' => Status::class,
        ];
    }
    
}
