<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'penalty_type',
        'amount',
        'reason',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'resolved_at' => 'datetime',
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

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isAppealed()
    {
        return $this->status === 'appealed';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function markAsResolved()
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function appeal()
    {
        $this->update(['status' => 'appealed']);
    }

    public function getPenaltyTypeText()
    {
        return match($this->penalty_type) {
            'late_payment' => 'Late Payment',
            'missed_payment' => 'Missed Payment',
            'group_violation' => 'Group Violation',
            default => ucfirst(str_replace('_', ' ', $this->penalty_type)),
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAppealed($query)
    {
        return $query->where('status', 'appealed');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }
}