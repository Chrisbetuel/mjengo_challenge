<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    /**
     * Display a listing of all challenges
     * GET /api/challenges
     */
    public function index()
    {
        $challenges = Challenge::withCount(['activeParticipants as active_participants_count'])
            ->with(['creator:id,username'])
            ->orderBy('created_at', 'desc') // Newest first
            ->get();

        return response()->json([
            'success' => true,
            'data' => $challenges
        ]);
    }

    /**
     * Display a specific challenge by ID
     * GET /api/challenges/{id}
     */
    public function show($id)
    {
        $challenge = Challenge::withCount(['activeParticipants as active_participants_count'])
            ->with(['creator:id,username'])
            ->find($id);

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

    /**
     * Allow authenticated user to join a challenge
     * POST /api/challenges/{id}/join
     */
    public function join(Request $request, $id)
    {
        $user = $request->user(); // From Sanctum

        $challenge = Challenge::find($id);

        if (!$challenge) {
            return response()->json([
                'success' => false,
                'message' => 'Challenge not found'
            ], 404);
        }

        if ($challenge->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This challenge is not active'
            ], 400);
        }

        // Check if full
        $activeCount = $challenge->activeParticipants()->count();
        if ($activeCount >= $challenge->max_participants) {
            return response()->json([
                'success' => false,
                'message' => 'Challenge is already full'
            ], 400);
        }

        // Check if already joined
        $alreadyJoined = $challenge->participants()->where('user_id', $user->id)->exists();
        if ($alreadyJoined) {
            return response()->json([
                'success' => false,
                'message' => 'You have already joined this challenge'
            ], 400);
        }

        // Join!
        $challenge->participants()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the challenge!',
            'data' => $challenge->fresh(['activeParticipants as active_participants_count']) // Refresh count
        ]);
    }
}