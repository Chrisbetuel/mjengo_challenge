<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\DirectPurchase;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\LipaKidogo;
use App\Models\LipaKidogoInstallment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Log activity
        ActivityLog::logMaterialPurchase(Auth::id(), $material->id, $totalAmount, 'direct');

        return redirect()->route('materials.show', $id)
            ->with('success', 'Purchase created successfully! Click "Pay Now" to complete your payment. Total amount: TZS ' . number_format($totalAmount, 2));
    }

    public function payNow(Request $request, $purchaseId)
    {
        $purchase = DirectPurchase::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($purchaseId);

        // For now, just mark as paid (payment gateway integration will be added later)
        $purchase->update([
            'status' => 'paid',
            'payment_reference' => 'PAY_' . time() . '_' . $purchase->id,
            'paid_at' => now(),
        ]);

        // Send notification
        Notification::create([
            'user_id' => $purchase->user_id,
            'title' => 'Purchase Confirmed',
            'message' => 'Your direct purchase of ' . $purchase->material->name . ' has been confirmed.',
            'type' => 'payment',
            'data' => ['purchase_id' => $purchase->id, 'material_name' => $purchase->material->name],
        ]);

        return redirect()->route('materials.index')
            ->with('success', 'Payment completed successfully! Your order will be processed soon.');
    }



    public function showDirectPurchase($id)
    {
        $directPurchase = DirectPurchase::where('user_id', Auth::id())
            ->with('material')
            ->findOrFail($id);

        return view('direct_purchases.show', compact('directPurchase'));
    }

    public function lipaKidogoPlans()
    {
        $lipaKidogoPlans = LipaKidogo::where('user_id', Auth::id())
            ->with('material')
            ->latest()
            ->get();

        return view('lipa_kidogo.index', compact('lipaKidogoPlans'));
    }

    public function showLipaKidogoPlan($id)
    {
        $lipaKidogo = LipaKidogo::where('user_id', Auth::id())
            ->with(['material', 'installments'])
            ->findOrFail($id);

        return view('lipa_kidogo.show', compact('lipaKidogo'));
    }

    public function lipaKidogoPurchase(Request $request, $id)
    {
        $material = Material::where('status', 'active')->findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'installments' => 'required|integer|in:3,6,12,24',
            'delivery_address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:20',
        ]);

        $totalAmount = $material->price * $request->quantity;
        $installmentAmount = $totalAmount / $request->installments;

        $lipaKidogo = LipaKidogo::create([
            'user_id' => Auth::id(),
            'material_id' => $material->id,
            'quantity' => $request->quantity,
            'unit_price' => $material->price,
            'total_amount' => $totalAmount,
            'num_installments' => $request->installments,
            'installment_amount' => $installmentAmount,
            'delivery_address' => $request->delivery_address,
            'phone_number' => $request->phone_number,
            'status' => 'active',
        ]);

        // Generate installments
        $this->generateInstallments($lipaKidogo);

        // Log activity
        ActivityLog::logMaterialPurchase(Auth::id(), $material->id, $totalAmount, 'lipa_kidogo');

        return redirect()->route('lipa_kidogo.show', $lipaKidogo->id)
            ->with('success', 'Lipa Kidogo plan created successfully! Your first installment is due now.');
    }

    public function payLipaKidogoInstallment(Request $request, $planId)
    {
        $lipaKidogo = LipaKidogo::where('user_id', Auth::id())->findOrFail($planId);

        $nextInstallment = $lipaKidogo->installments()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();

        if (!$nextInstallment) {
            return redirect()->back()->with('error', 'No pending installments found.');
        }

        // For now, just mark as paid (payment gateway integration will be added later)
        $nextInstallment->update([
            'status' => 'paid',
            'payment_reference' => 'LK_PAY_' . time() . '_' . $nextInstallment->id,
            'paid_at' => now(),
        ]);

        // Update paid amount in LipaKidogo
        $lipaKidogo->increment('paid_amount', $nextInstallment->amount);

        // Check if all installments are paid
        $pendingInstallments = $lipaKidogo->installments()->where('status', 'pending')->count();
        if ($pendingInstallments == 0) {
            $lipaKidogo->update(['status' => 'completed']);
        }

        // Send notification
        Notification::create([
            'user_id' => $lipaKidogo->user_id,
            'title' => 'Installment Paid',
            'message' => 'Your Lipa Kidogo installment for ' . $lipaKidogo->material->name . ' has been paid.',
            'type' => 'payment',
            'data' => ['plan_id' => $lipaKidogo->id, 'installment_id' => $nextInstallment->id],
        ]);

        return redirect()->route('lipa_kidogo.show', $planId)
            ->with('success', 'Installment paid successfully!');
    }

    private function generateInstallments(LipaKidogo $lipaKidogo)
    {
        $dueDate = now();

        for ($i = 1; $i <= $lipaKidogo->installments; $i++) {
            LipaKidogoInstallment::create([
                'lipa_kidogo_id' => $lipaKidogo->id,
                'user_id' => $lipaKidogo->user_id,
                'material_id' => $lipaKidogo->material_id,
                'installment_number' => $i,
                'amount' => $lipaKidogo->installment_amount,
                'due_date' => $dueDate,
                'status' => $i == 1 ? 'pending' : 'upcoming', // First installment is pending immediately
            ]);

            $dueDate = $dueDate->copy()->addMonths(1); // Monthly installments
        }
    }
}
