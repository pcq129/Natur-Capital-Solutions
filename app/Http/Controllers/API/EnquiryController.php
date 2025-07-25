<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\EnquiryRequest;
use App\Models\Enquiry;
use App\Events\UserEnquiry; // Assuming you have an event for enquiry submission


class EnquiryController extends Controller
{
    public function enquire(EnquiryRequest $request){
        $enquiryData = $request->validated();

        $enquiry = Enquiry::create([
            'product_id' => $enquiryData['product_id'],
            'user_id' => auth()->id(), // Assuming the user is authenticated
            'message' => $enquiryData['message'] ?? null,
            'quantity' => $enquiryData['quantity'],
        ]);

        // For handling email and other side tasks, fire an event (currently disabled due to mailtrap limits, working fine though)
        // event(new UserEnquiry($enquiry, auth()->user()));

        return response()->json([
            'message' => 'Enquiry submitted successfully',
            'enquiry' => $enquiry->only(['id', 'product_id', 'user_id', 'message', 'quantity', 'created_at'])
        ]);
    }
}
