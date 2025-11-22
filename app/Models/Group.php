<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'max_members',
        'status',
        'challenge_id',
    ];

    // Relationships
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function activeMembers()
    {
        return $this->members()->where('group_members.status', 'active');
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

    public function getMemberCount()
    {
        return $this->activeMembers()->count();
    }

    public function hasAvailableSlots()
    {
        return $this->getMemberCount() < $this->max_members;
    }

    public function isUserMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    public function isUserLeader($userId)
    {
        return $this->leader_id === $userId;
    }

    public function addMember($userId, $status = 'invited')
    {
        if (!$this->hasAvailableSlots()) {
            return false;
        }

        return $this->members()->create([
            'user_id' => $userId,
            'status' => $status,
        ]);
    }

    public function removeMember($userId)
    {
        return $this->members()->where('user_id', $userId)->delete();
    }
}
