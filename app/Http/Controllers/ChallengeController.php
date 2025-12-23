<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\ActivityLog;
use App\Models\Notification;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChallengeController extends Controller
{
    public function index()
    {
        $builder = Challenge::query();

        if (Schema::hasColumn('challenges', 'status')) {
            $builder->where('challenges.status', 'active');
        }

        $challenges = $builder->withCount(['participants as active_participants_count' => function ($query) {
                $query->where('participants.status', 'active');
            }])
            ->with('creator')
            ->latest()
            ->get();

        $userChallenges = Auth::check() ? Auth::user()->getActiveChallenges() : collect();

        return view('challenges.index', compact('challenges', 'userChallenges'));
    }

    public function show($id)
    {
        $challenge = Challenge::with(['creator', 'participants.user'])
            ->withCount(['participants as active_participants_count' => function ($query) {
                $query->where('participants.status', 'active');
            }])
            ->findOrFail($id);

        $isParticipant = Auth::check() ? $challenge->participants()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->exists() : false;

        $userParticipant = Auth::check() ? $challenge->participants()
            ->where('user_id', Auth::id())
            ->first() : null;

        $totalCollected = $challenge->getTotalCollected();
        $availableSlots = $challenge->max_participants - $challenge->active_participants_count;

        return view('challenges.show', compact(
            'challenge', 
            'isParticipant', 
            'userParticipant',
            'totalCollected',
            'availableSlots'
        ));
    }

    public function participate(Request $request, $id)
    {
        \Log::info('Participate method called', ['challenge_id' => $id, 'user_id' => Auth::id()]);

        $challenge = Schema::hasColumn('challenges', 'status')
            ? Challenge::where('challenges.status', 'active')->findOrFail($id)
            : Challenge::findOrFail($id);
        $user = Auth::user();

        \Log::info('Challenge found', ['challenge' => $challenge->id]);

        // Check if user is already participating
        $existingParticipant = Participant::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->where('participants.status', 'active')
            ->first();

        if ($existingParticipant) {
            \Log::info('User already participating', ['user_id' => $user->id, 'challenge_id' => $challenge->id]);
            return redirect()->back()->with('error', 'You are already participating in this challenge.');
        }

        // Check available slots
        if ($challenge->getAvailableSlots() <= 0) {
            \Log::info('Challenge full', ['challenge_id' => $challenge->id]);
            return redirect()->back()->with('error', 'This challenge is full. Please try another one.');
        }

        // Get the next queue position
        $lastPosition = Participant::where('challenge_id', $challenge->id)
            ->max('queue_position') ?? 0;

        \Log::info('Creating participant', ['user_id' => $user->id, 'challenge_id' => $challenge->id, 'queue_position' => $lastPosition + 1]);

        // Create participant record
        $participant = Participant::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'queue_position' => $lastPosition + 1,
            'status' => 'active',
            'join_attempt' => 1,
        ]);

        \Log::info('Participant created', ['participant_id' => $participant->id]);

        // Log activity
        ActivityLog::logChallengeJoin($user->id, $challenge->id);

        return redirect()->route('challenges.show', $challenge->id)
            ->with('success', 'Successfully joined the challenge!');
    }












}
