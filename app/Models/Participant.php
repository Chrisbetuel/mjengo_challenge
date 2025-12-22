<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'queue_position',
        'status',
        'join_attempt',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Helper methods
    public function getTotalPaid()
    {
        return $this->payments()
            ->where('status', 'paid')
            ->sum('amount');
    }

    public function getPendingPayments()
    {
        return $this->payments()
            ->where('status', 'pending')
            ->get();
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    // Debt calculation methods
    public function getExpectedPayments()
    {
        $challenge = $this->challenge;
        $startDate = $this->created_at->toDateString(); // Use join date or challenge start date
        $endDate = min(now()->toDateString(), $challenge->end_date->toDateString());

        $daysElapsed = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;

        return $daysElapsed * $challenge->daily_amount;
    }

    public function getAccumulatedDebt()
    {
        $expected = $this->getExpectedPayments();
        $paid = $this->getTotalPaid();

        return max(0, $expected - $paid);
    }

    public function getMissedPaymentDays()
    {
        $challenge = $this->challenge;
        $startDate = $this->created_at->toDateString();
        $endDate = min(now()->toDateString(), $challenge->end_date->toDateString());

        $totalDays = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;

        // Count days with payments
        $paidDays = $this->payments()
            ->where('status', 'paid')
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->count();

        return max(0, $totalDays - $paidDays);
    }

    public function getDebtBreakdown()
    {
        $expected = $this->getExpectedPayments();
        $paid = $this->getTotalPaid();
        $debt = $this->getAccumulatedDebt();
        $missedDays = $this->getMissedPaymentDays();

        return [
            'expected_total' => $expected,
            'paid_total' => $paid,
            'accumulated_debt' => $debt,
            'missed_days' => $missedDays,
            'daily_amount' => $this->challenge->daily_amount,
            'debt_per_day' => $missedDays > 0 ? $debt / $missedDays : 0,
        ];
    }

    public function hasDebt()
    {
        return $this->getAccumulatedDebt() > 0;
    }
}