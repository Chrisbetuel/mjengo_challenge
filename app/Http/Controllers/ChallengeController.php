<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Services\SelcomService;
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

    public function makePayment(Request $request, $challengeId)
    {
        $challenge = Challenge::findOrFail($challengeId);
        $user = Auth::user();

        $participant = Participant::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->where('participants.status', 'active')
            ->firstOrFail();



        $paymentType = $request->payment_type ?? 'direct';
        $selcomService = new SelcomService();

        try {
            if ($paymentType === 'lipa_kidogo') {
                return $this->initiateLipaKidogoPayment($participant, $challenge, $selcomService);
            } else {
                return $this->initiateDirectPayment($participant, $challenge, $selcomService);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    private function initiateDirectPayment(Participant $participant, Challenge $challenge, SelcomService $selcomService)
    {
        // Create payment record
        $payment = Payment::create([
            'participant_id' => $participant->id,
            'amount' => $challenge->daily_amount,
            'payment_date' => today(),
            'status' => 'pending',
            'payment_method' => 'selcom',
            'payment_type' => 'direct',
        ]);

        // Generate unique order ID
        $orderId = 'CHALLENGE_' . $participant->id . '_' . time();

        try {
            // Initiate Selcom payment
            $result = $selcomService->initiatePayment(
                $orderId,
                $challenge->daily_amount,
                $participant->user->phone ?? '',
                'Challenge Payment - ' . $challenge->title,
                route('selcom.callback'),
                $participant->user->email ?? null,
                $participant->user->name ?? null,
                route('challenges.show', $challenge->id),
                route('challenges.show', $challenge->id)
            );

            $payment->update([
                'selcom_order_id' => $orderId,
                'transaction_id' => $orderId,
            ]);

            return redirect()->away($result['payment_url']);
        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            return redirect()->back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    private function initiateLipaKidogoPayment(Participant $participant, Challenge $challenge, SelcomService $selcomService)
    {
        // Calculate total challenge amount and installments
        $totalAmount = $challenge->daily_amount * $challenge->duration_days;
        $installments = $challenge->duration_days; // One payment per day
        $installmentAmount = $challenge->daily_amount;

        // Create payment record for first installment
        $payment = Payment::create([
            'participant_id' => $participant->id,
            'amount' => $challenge->daily_amount,
            'payment_date' => today(),
            'status' => 'pending',
            'payment_method' => 'selcom',
            'payment_type' => 'lipa_kidogo',
            'installment_number' => 1,
            'total_installments' => $installments,
        ]);

        // Generate unique order ID
        $orderId = 'LIPAKIDOGO_' . $participant->id . '_' . time();

        try {
            // Initiate lipa kidogo payment
            $result = $selcomService->initiateLipaKidogo(
                $orderId,
                $totalAmount,
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
                return redirect()->back()->with('error', 'Lipa Kidogo payment initiation failed: Invalid response from payment gateway');
            }
        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            return redirect()->back()->with('error', 'Lipa Kidogo payment initiation failed: ' . $e->getMessage());
        }
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

    /**
     * Handle Selcom payment callback
     */
    public function handleSelcomCallback(Request $request)
    {
        $selcomService = new SelcomService();

        if (!$selcomService->validateCallback($request->all())) {
            return response()->json(['status' => 'error', 'message' => 'Invalid callback data'], 400);
        }

        $orderId = $request->order_id;
        $status = $request->status;
        $transId = $request->transid ?? null;

        // Find payment by Selcom order ID
        $payment = Payment::where('selcom_order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Update payment status based on Selcom response
        if ($status === 'success' || $status === 'paid') {
            $payment->update([
                'status' => 'paid',
                'selcom_trans_id' => $transId,
                'paid_at' => now(),
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
        } elseif ($status === 'failed') {
            $payment->update([
                'status' => 'failed',
                'selcom_trans_id' => $transId,
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle Selcom webhook
     */
    public function handleSelcomWebhook(Request $request)
    {
        // Webhook handling (similar to callback but for background processing)
        return $this->handleSelcomCallback($request);
    }
}
