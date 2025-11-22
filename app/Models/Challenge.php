<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'daily_amount',
        'max_participants',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_amount' => 'decimal:2',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function activeParticipants()
    {
        return $this->hasMany(Participant::class)->where('participants.status', '=', 'active');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Participant::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    // Helper methods
    public function getTotalCollected()
    {
        return $this->payments()
            ->where('payments.status', 'paid')
            ->sum('amount');
    }

    public function getAvailableSlots()
    {
        return $this->max_participants - ($this->active_participants_count ?? 0);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}