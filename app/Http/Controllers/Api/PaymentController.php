<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Get payments by user ID
     */
    public function getUserPayments($user_id)
    {
        $payments = Payment::where('user_id', $user_id)->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }
}
