<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Participant;
use App\Services\SelcomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $selcomService;

    public function __construct(SelcomService $selcomService)
    {
        $this->selcomService = $selcomService;
    }

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

    /**
     * Create a new payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:direct,lipa_kidogo',
            'installment_number' => 'nullable|integer|min:1',
            'total_installments' => 'nullable|integer|min:1',
        ]);

        try {
            $participant = Participant::findOrFail($request->participant_id);

            // Verify the authenticated user owns this participant record
            if ($participant->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to participant'
                ], 403);
            }

            // Create payment record
            $payment = Payment::create([
                'participant_id' => $participant->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'payment_date' => now(),
                'payment_method' => 'manual', // Changed from 'selcom' to 'manual'
                'payment_type' => $request->payment_type,
                'installment_number' => $request->installment_number,
                'total_installments' => $request->total_installments,
            ]);

            Log::info('Payment created successfully', [
                'payment_id' => $payment->id,
                'participant_id' => $participant->id,
                'amount' => $request->amount
            ]);

            return response()->json([
                'success' => true,
                'data' => $payment,
                'message' => 'Payment created successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment creation failed', [
                'user_id' => Auth::id(),
                'participant_id' => $request->participant_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment initiation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payments by challenge ID
     */
    public function getChallengePayments($challenge_id)
    {
        $payments = Payment::whereHas('participant', function ($query) use ($challenge_id) {
            $query->where('challenge_id', $challenge_id);
        })->with('participant.user')->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }
}
