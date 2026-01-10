<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LipaKidogo;
use App\Models\LipaKidogoInstallment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Get all Lipa Kidogo plans for the authenticated user
     */
    public function getUserLipaKidogo(Request $request)
    {
        $user = $request->user();

        $plans = LipaKidogo::with(['material', 'installments'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $plans,
        ]);
    }

    /**
     * Get all installments for a specific plan
     */
    public function getInstallments(Request $request, $planId)
    {
        $user = $request->user();

        $plan = LipaKidogo::with('installments')
            ->where('user_id', $user->id)
            ->findOrFail($planId);

        return response()->json([
            'success' => true,
            'data' => $plan->installments,
        ]);
    }

    /**
     * Pay a specific installment
     */
    public function payInstallment(Request $request, $installmentId)
    {
        $user = $request->user();

        $installment = LipaKidogoInstallment::with('lipaKidogo')
            ->where('user_id', $user->id)
            ->findOrFail($installmentId);

        if ($installment->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Installment already paid',
            ], 400);
        }

        // Optionally, check payment amount
        $amountPaid = $request->input('amount', $installment->amount);

        // Here you can integrate real payment logic (e.g., ZenoPay, PesaPal, Stripe)
        // For now, we just mark it as paid
        $installment->markAsPaid(Carbon::now());

        return response()->json([
            'success' => true,
            'message' => 'Installment paid successfully',
            'data' => $installment,
        ]);
    }

    /**
     * Get all payments for a challenge (optional)
     */
    public function getChallengePayments($challengeId)
    {
        // You can implement this if your plans are linked to challenges
        return response()->json([
            'success' => true,
            'data' => [],
        ]);
    }
}
