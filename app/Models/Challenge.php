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
        return $this->participants()->where('status', 'active');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Participant::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    // Helper Accessors for API (automatically included in JSON)
    public function getActiveParticipantsCountAttribute()
    {
        return $this->activeParticipants()->count();
    }

    public function getAvailableSlotsAttribute()
    {
        return $this->max_participants - $this->getActiveParticipantsCountAttribute();
    }

    public function getTotalCollectedAttribute()
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getDurationDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getTotalTargetAttribute()
    {
        return $this->daily_amount * $this->duration_days * $this->max_participants;
    }
}