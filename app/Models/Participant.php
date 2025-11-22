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
}