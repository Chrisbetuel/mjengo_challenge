<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\DirectPurchase;
use App\Models\LipaKidogo;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Services\SelcomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::where('status', 'active')
            ->with('creator')
            ->latest()
            ->get();

        return view('materials.index', compact('materials'));
    }

    public function show($id)
    {
        $material = Material::with('creator')->findOrFail($id);
        return view('materials.show', compact('material'));
    }

    public function directPurchase(Request $request, $id)
    {
        $material = Material::where('status', 'active')->findOrFail($id);
        
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:20',
        ]);

        $totalAmount = $material->price * $request->quantity;

        $purchase = DirectPurchase::create([
            'user_id' => Auth::id(),
            'material_id' => $material->id,
            'quantity' => $request->quantity,
            'unit_price' => $material->price,
            'total_amount' => $totalAmount,
            'delivery_address' => $request->delivery_address,
            'phone_number' => $request->phone_number,
            'status' => 'pending',
        ]);

        // Process payment
        $this->processDirectPurchasePayment($purchase);

        // Log activity
        ActivityLog::logMaterialPurchase(Auth::id(), $material->id, $totalAmount, 'direct');

        return redirect()->route('materials.index')
            ->with('success', 'Purchase initiated successfully! Total amount: TZS ' . number_format($totalAmount, 2));
    }

    public function lipaKidogoPurchase(Request $request, $id)
    {
        $material = Material::where('status', 'active')->findOrFail($id);

        $request->validate([
            'user_type' => 'required|in:businessman,employed',
            'payment_duration' => 'required|in:daily,weekly,monthly',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        $totalAmount = $material->price;
        $installmentAmount = $this->calculateInstallmentAmount($totalAmount, $request->payment_duration);
        $numInstallments = $this->calculateNumberOfInstallments($totalAmount, $installmentAmount);

        $lipaKidogo = LipaKidogo::create([
            'user_id' => Auth::id(),
            'material_id' => $material->id,
            'total_amount' => $totalAmount,
            'installment_amount' => $installmentAmount,
            'num_installments' => $numInstallments,
            'start_date' => $request->start_date,
            'user_type' => $request->user_type,
            'payment_duration' => $request->payment_duration,
            'status' => 'pending',
        ]);

        // Create installments
        $this->createInstallments($lipaKidogo, $request->start_date, $request->payment_duration);

        // Generate unique order ID
        $orderId = 'MATERIAL_LIPAKIDOGO_' . $lipaKidogo->id . '_' . time();

        $selcomService = new SelcomService();

        try {
            // Initiate Selcom lipa kidogo payment
            $result = $selcomService->initiateLipaKidogo(
                $orderId,
                $totalAmount,
                $installmentAmount,
                $numInstallments,
                Auth::user()->phone_number ?? '',
                Auth::user()->email ?? null,
                Auth::user()->name ?? null,
                route('materials.index'),
                route('materials.index')
            );

            $lipaKidogo->update([
                'selcom_order_id' => $orderId,
            ]);

            // Log activity
            ActivityLog::logMaterialPurchase(Auth::id(), $material->id, $totalAmount, 'installment');

            return redirect()->away($result['payment_url']);
        } catch (\Exception $e) {
            $lipaKidogo->update(['status' => 'failed']);
            return redirect()->route('materials.index')
                ->with('error', 'Lipa Kidogo payment initiation failed: ' . $e->getMessage());
        }
    }

    private function calculateInstallmentAmount($totalAmount, $duration)
    {
        return match($duration) {
            'daily' => $totalAmount / 30, // Approximate 30 days
            'weekly' => $totalAmount / 4, // 4 weeks
            'monthly' => $totalAmount / 3, // 3 months
            default => $totalAmount / 30,
        };
    }

    private function calculateNumberOfInstallments($totalAmount, $installmentAmount)
    {
        $installments = ceil($totalAmount / $installmentAmount);
        return max(2, min($installments, 36)); // Between 2 and 36 installments
    }

    private function createInstallments(LipaKidogo $lipaKidogo, $startDate, $paymentDuration)
    {
        $numInstallments = $lipaKidogo->num_installments;
        $installmentAmount = $lipaKidogo->installment_amount;
        $currentDate = Carbon::parse($startDate);

        for ($i = 1; $i <= $numInstallments; $i++) {
            // Calculate due date based on payment duration
            $dueDate = match($paymentDuration) {
                'daily' => $currentDate->copy()->addDays($i - 1),
                'weekly' => $currentDate->copy()->addWeeks($i - 1),
                'monthly' => $currentDate->copy()->addMonths($i - 1),
                default => $currentDate->copy()->addDays($i - 1),
            };

            \App\Models\LipaKidogoInstallment::create([
                'lipa_kidogo_id' => $lipaKidogo->id,
                'user_id' => $lipaKidogo->user_id,
                'material_id' => $lipaKidogo->material_id,
                'installment_number' => $i,
                'amount' => $installmentAmount,
                'due_date' => $dueDate,
                'status' => 'pending',
            ]);
        }
    }

    public function showLipaKidogo($id)
    {
        $lipaKidogo = LipaKidogo::where('user_id', Auth::id())
            ->with(['material', 'installments' => function($query) {
                $query->orderBy('installment_number');
            }])
            ->findOrFail($id);

        return view('lipa_kidogo.show', compact('lipaKidogo'));
    }

    public function payLipaKidogoInstallment(Request $request, $lipaKidogoId, $installmentId)
    {
        $lipaKidogo = LipaKidogo::where('user_id', Auth::id())
            ->with('material')
            ->findOrFail($lipaKidogoId);

        $installment = $lipaKidogo->installments()->findOrFail($installmentId);

        // Check if installment is already paid
        if ($installment->status === 'paid') {
            return redirect()->back()->with('error', 'This installment has already been paid.');
        }

        // Check if installment is overdue (optional - you can remove this check if you want to allow overdue payments)
        if ($installment->status === 'overdue') {
            return redirect()->back()->with('error', 'This installment is overdue. Please contact support.');
        }

        $selcomService = new SelcomService();

        // Generate unique order ID
        $orderId = 'LIPAKIDOGO_INSTALLMENT_' . $installment->id . '_' . time();

        try {
            // Initiate Selcom payment for this specific installment
            $result = $selcomService->initiatePayment(
                $orderId,
                $installment->amount,
                Auth::user()->phone_number ?? '',
                'Lipa Kidogo Installment Payment - ' . $lipaKidogo->material->name . ' (#' . $installment->installment_number . ')',
                route('lipa_kidogo.callback'),
                Auth::user()->email ?? null,
                Auth::user()->name ?? null,
                route('lipa_kidogo.show', $lipaKidogo->id),
                route('lipa_kidogo.show', $lipaKidogo->id)
            );

            // Update installment with payment details
            $installment->update([
                'selcom_order_id' => $orderId,
                'status' => 'pending',
            ]);

            return redirect()->away($result['payment_url']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    public function handleLipaKidogoCallback(Request $request)
    {
        $selcomService = new SelcomService();

        if (!$selcomService->validateCallback($request->all())) {
            return response()->json(['status' => 'error', 'message' => 'Invalid callback data'], 400);
        }

        $orderId = $request->order_id;
        $status = $request->status;
        $transId = $request->transid ?? null;

        // Find installment by Selcom order ID
        $installment = \App\Models\LipaKidogoInstallment::where('selcom_order_id', $orderId)->first();

        if (!$installment) {
            return response()->json(['status' => 'error', 'message' => 'Installment not found'], 404);
        }

        // Update installment status based on Selcom response
        if ($status === 'success' || $status === 'paid') {
            $installment->update([
                'status' => 'paid',
                'selcom_trans_id' => $transId,
                'paid_at' => now(),
            ]);

            // Update Lipa Kidogo total paid amount
            $lipaKidogo = $installment->lipaKidogo;
            $totalPaid = $lipaKidogo->installments()->where('status', 'paid')->sum('amount');
            $lipaKidogo->update(['paid_amount' => $totalPaid]);

            // Check if all installments are paid
            $totalInstallments = $lipaKidogo->installments()->count();
            $paidInstallments = $lipaKidogo->installments()->where('status', 'paid')->count();

            if ($paidInstallments === $totalInstallments) {
                $lipaKidogo->update(['status' => 'completed']);
            } elseif ($paidInstallments > 0) {
                $lipaKidogo->update(['status' => 'active']);
            }

            // Log activity
            ActivityLog::logLipaKidogoPayment($lipaKidogo->user_id, $installment->amount, $lipaKidogo->id, $installment->installment_number);

            // Send notification
            Notification::create([
                'user_id' => $lipaKidogo->user_id,
                'title' => 'Installment Payment Successful',
                'message' => 'Your Lipa Kidogo installment payment of TZS ' . number_format($installment->amount, 2) . ' for ' . $lipaKidogo->material->name . ' was processed successfully.',
                'type' => 'payment',
                'data' => ['amount' => $installment->amount, 'lipa_kidogo_id' => $lipaKidogo->id, 'installment_number' => $installment->installment_number],
            ]);
        } elseif ($status === 'failed') {
            $installment->update([
                'status' => 'failed',
                'selcom_trans_id' => $transId,
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function showDirectPurchase($id)
    {
        $directPurchase = DirectPurchase::where('user_id', Auth::id())
            ->with('material')
            ->findOrFail($id);

        return view('direct_purchases.show', compact('directPurchase'));
    }

    private function processDirectPurchasePayment(DirectPurchase $purchase)
    {
        // Simulate payment processing
        sleep(2);

        $purchase->update([
            'status' => 'paid',
            'payment_reference' => 'DP_' . time() . '_' . $purchase->id,
        ]);

        // Send notification
        \App\Models\Notification::create([
            'user_id' => $purchase->user_id,
            'title' => 'Purchase Confirmed',
            'message' => 'Your direct purchase of ' . $purchase->material->name . ' has been confirmed.',
            'type' => 'payment',
            'data' => ['purchase_id' => $purchase->id, 'material_name' => $purchase->material->name],
        ]);
    }
}
