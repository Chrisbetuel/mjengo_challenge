<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    /**
     * Display a listing of challenges
     */
    public function index()
    {
        $challenges = Challenge::all();

        return response()->json([
            'success' => true,
            'data' => $challenges
        ]);
    }

    /**
     * Display a specific challenge by ID
     */
    public function show($id)
    {
        $challenge = Challenge::with('creator')->find($id);

        if (!$challenge) {
            return response()->json([
                'success' => false,
                'message' => 'Challenge not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $challenge
        ]);
    }
}
