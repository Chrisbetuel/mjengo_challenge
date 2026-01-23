<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ← ADD THIS LINE

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ← CHANGE THIS LINE

    protected $fillable = [
        'user_id',
        'username',
        'email',
        'password',
        'phone_number',
        'nida_id',
        'role',
        'reset_token',
        
        'otp_code',
        'otp_expires_at',
        'otp_attempts',
        'otp_last_sent_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ best practice
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

    public function getChallengeDebt()
    {
        return $this->participants()
            ->where('participants.status', 'active')
            ->whereHas('challenge', function ($query) {
                $query->where('status', 'active');
            })
            ->join('payments', 'participants.id', '=', 'payments.participant_id')
            ->where('payments.status', '!=', 'paid')
            ->sum('payments.amount');
    }

    public function getLipaKidogoDebt()
    {
        return $this->lipaKidogoPlans()
            ->where('lipa_kidogo.status', 'active')
            ->join('lipa_kidogo_installments', 'lipa_kidogo.id', '=', 'lipa_kidogo_installments.lipa_kidogo_id')
            ->where('lipa_kidogo_installments.status', '!=', 'paid')
            ->sum('lipa_kidogo_installments.amount');
    }

    public function getTotalDebt()
    {
        return $this->getChallengeDebt() + $this->getLipaKidogoDebt();
    }

    public function getDetailedChallengeDebts()
    {
        return $this->participants()
            ->where('participants.status', 'active')
            ->whereHas('challenge', function ($query) {
                $query->where('status', 'active');
            })
            ->join('payments', 'participants.id', '=', 'payments.participant_id')
            ->join('challenges', 'participants.challenge_id', '=', 'challenges.id')
            ->where('payments.status', '!=', 'paid')
            ->select(
                'challenges.name as challenge_name',
                'payments.amount',
                'payments.installment_number',
                'payments.total_installments',
                'payments.created_at as due_date',
                'payments.status as payment_status'
            )
            ->get();
    }

    public function getDetailedLipaKidogoDebts()
    {
        return $this->lipaKidogoPlans()
            ->where('lipa_kidogo.status', 'active')
            ->join('lipa_kidogo_installments', 'lipa_kidogo.id', '=', 'lipa_kidogo_installments.lipa_kidogo_id')
            ->join('materials', 'lipa_kidogo.material_id', '=', 'materials.id')
            ->where('lipa_kidogo_installments.status', '!=', 'paid')
            ->select(
                'materials.name as material_name',
                'lipa_kidogo_installments.amount',
                'lipa_kidogo_installments.installment_number',
                'lipa_kidogo.num_installments as total_installments',
                'lipa_kidogo_installments.due_date',
                'lipa_kidogo_installments.status as installment_status'
            )
            ->get();
    }
}
