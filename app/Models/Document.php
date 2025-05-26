<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    public function documentable(): MorphTo{
        return $this->mrophTo();
    }
}
