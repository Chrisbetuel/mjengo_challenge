<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Payment;
use App\Models\Participant;
use App\Models\LipaKidogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeChallenges = $user->participants()
            ->with('challenge')
            ->where('participants.status', 'active')
            ->get();

        $recentPayments = Payment::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->take(5)->get();

        $totalSavings = Payment::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('payments.status', 'paid')->sum('amount');

        $lipaKidogoPlans = LipaKidogo::where('user_id', $user->id)
            ->with('material')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('activeChallenges', 'recentPayments', 'totalSavings', 'lipaKidogoPlans'));
    }
}