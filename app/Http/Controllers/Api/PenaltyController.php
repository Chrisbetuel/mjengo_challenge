<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penalty;

class PenaltyController extends Controller
{
    /**
     * Get penalties by user ID
     */
    public function getUserPenalties($user_id)
    {
        $penalties = Penalty::where('user_id', $user_id)->get();

        return response()->json([
            'success' => true,
            'data' => $penalties
        ]);
    }
}
