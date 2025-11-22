<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\DirectPurchase;
use App\Models\LipaKidogo;
use App\Models\Notification;
use App\Models\ActivityLog;
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
            'status' => 'active',
        ]);

        // Log activity
        ActivityLog::logMaterialPurchase(Auth::id(), $material->id, $totalAmount, 'installment');

        return redirect()->route('dashboard')
            ->with('success', 'Lipa Kidogo plan created successfully! You will pay TZS ' . 
                   number_format($installmentAmount, 2) . ' ' . $request->payment_duration . ' for ' . 
                   $numInstallments . ' installments.');
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