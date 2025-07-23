<?php

namespace App\Http\Controllers\Admin;

use App\Events\EnquiredByUser;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EnquiredByUser $request)
    {
        //
    }
}
