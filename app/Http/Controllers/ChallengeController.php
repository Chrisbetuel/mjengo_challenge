<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Participant;
use App\Models\Payment;
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
            ->where('participants.status', 'active')
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

    public function join(Request $request, $id)
    {
        $challenge = Schema::hasColumn('challenges', 'status')
            ? Challenge::where('challenges.status', 'active')->findOrFail($id)
            : Challenge::findOrFail($id);
        $user = Auth::user();

        // Check if user is already participating
        $existingParticipant = Participant::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->where('participants.status', 'active')
            ->first();

        if ($existingParticipant) {
            return redirect()->back()->with('error', 'You are already participating in this challenge.');
        }

        // Check available slots
        if ($challenge->getAvailableSlots() <= 0) {
            return redirect()->back()->with('error', 'This challenge is full. Please try another one.');
        }

        // Get the next queue position
        $lastPosition = Participant::where('challenge_id', $challenge->id)
            ->max('queue_position') ?? 0;

        // Create participant record
        $participant = Participant::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'queue_position' => $lastPosition + 1,
            'status' => 'active',
            'join_attempt' => 1,
        ]);

        // Log activity
        ActivityLog::logChallengeJoin($user->id, $challenge->id);

        return redirect()->route('challenges.show', $challenge->id)
            ->with('success', 'Successfully joined the challenge!');
    }

    public function makePayment(Request $request, $challengeId)
    {
        $challenge = Challenge::findOrFail($challengeId);
        $user = Auth::user();

        $participant = Participant::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->where('participants.status', 'active')
            ->firstOrFail();

        // Check if payment already exists for today
        $existingPayment = Payment::where('participant_id', $participant->id)
            ->whereDate('payment_date', today())
            ->first();

        if ($existingPayment) {
            return redirect()->back()->with('error', 'You have already made a payment for today.');
        }

        // Create payment record
        $payment = Payment::create([
            'participant_id' => $participant->id,
            'amount' => $challenge->daily_amount,
            'payment_date' => today(),
            'status' => 'pending',
            'payment_method' => $request->payment_method ?? 'mobile_money',
        ]);

        // Process payment (this would integrate with actual payment gateway)
        // For now, we'll simulate successful payment
        $this->processPayment($payment);

        return redirect()->back()->with('success', 'Payment initiated successfully!');
    }

    private function processPayment(Payment $payment)
    {
        // Simulate payment processing
        // In real implementation, this would call payment gateway API
        sleep(2); // Simulate API call delay

        $payment->update([
            'status' => 'paid',
            'transaction_id' => 'TXN_' . time() . '_' . $payment->id,
        ]);

        // Log activity
        ActivityLog::logPayment($payment->participant->user_id, $payment->amount, $payment->participant->challenge_id);

        // Send notification
        Notification::create([
            'user_id' => $payment->participant->user_id,
            'title' => 'Payment Successful',
            'message' => 'Your payment of TZS ' . number_format($payment->amount, 2) . ' was processed successfully.',
            'type' => 'payment',
            'data' => ['amount' => $payment->amount, 'challenge_id' => $payment->participant->challenge_id],
        ]);
    }
}