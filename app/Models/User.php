<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'phone_number',
        'nida_id',
        'role',
        'reset_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function challengesCreated()
    {
        return $this->hasMany(Challenge::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Participant::class);
    }

    public function directPurchases()
    {
        return $this->hasMany(DirectPurchase::class);
    }

    public function lipaKidogoPlans()
    {
        return $this->hasMany(LipaKidogo::class);
    }

    public function lipaKidogoInstallments()
    {
        return $this->hasMany(LipaKidogoInstallment::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    public function groupsLed()
    {
        return $this->hasMany(Group::class, 'leader_id');
    }

    public function groupMemberships()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function materialsCreated()
    {
        return $this->hasMany(Material::class, 'created_by');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getActiveChallenges()
    {
        return $this->participants()
            ->whereHas('challenge', function ($query) {
                $query->where('status', 'active');
            })
            ->with('challenge')
            ->get();
    }

    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->unread()->count();
    }

    public function getTotalSavings()
    {
        return $this->payments()
            ->where('payments.status', 'paid')
            ->sum('amount');
    }

    public function getActivePenalties()
    {
        return $this->penalties()->active()->get();
    }

    public function getActiveGroups()
    {
        return $this->groupMemberships()
            ->whereHas('group', function ($query) {
                $query->where('status', 'active');
            })
            ->with('group')
            ->get();
    }
}