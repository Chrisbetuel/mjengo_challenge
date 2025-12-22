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









    private function initiateDebtInstallmentPayment(Participant $participant, Challenge $challenge, SelcomService $selcomService)
    {
        $debtBreakdown = $participant->getDebtBreakdown();
        $debtAmount = $debtBreakdown['accumulated_debt'];

        // Calculate installments (split debt into manageable payments)
        $installments = min(10, max(1, ceil($debtAmount / $challenge->daily_amount))); // Max 10 installments
        $installmentAmount = ceil($debtAmount / $installments);

        // Create payment record for first debt installment
        $payment = Payment::create([
            'participant_id' => $participant->id,
            'amount' => $installmentAmount,
            'payment_date' => today(),
            'status' => 'pending',
            'payment_method' => 'selcom',
            'payment_type' => 'debt_installment',
            'installment_number' => 1,
            'total_installments' => $installments,
        ]);

        // Generate unique order ID
        $orderId = 'DEBT_INSTALLMENT_' . $participant->id . '_' . time();

        try {
            // Initiate lipa kidogo payment for debt installments
            $result = $selcomService->initiateLipaKidogo(
                $orderId,
                $debtAmount,
                $installmentAmount,
                $installments,
                $participant->user->phone ?? '',
                $participant->user->email ?? null,
                $participant->user->name ?? null,
                route('challenges.show', $challenge->id),
                route('challenges.show', $challenge->id)
            );

            // Check if response contains payment URL
            if (isset($result['payment_url']) && !empty($result['payment_url'])) {
                $payment->update([
                    'selcom_order_id' => $orderId,
                    'transaction_id' => $orderId,
                ]);

                return redirect()->away($result['payment_url']);
            } else {
                $payment->update(['status' => 'failed']);
                return redirect()->back()->with('error', 'Debt installment payment initiation failed: Invalid response from payment gateway');
            }
        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            return redirect()->back()->with('error', 'Debt installment payment initiation failed: ' . $e->getMessage());
        }
    }


}
