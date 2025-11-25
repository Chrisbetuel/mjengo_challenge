<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LipaKidogoInstallment extends Model
{
    use HasFactory;

    protected $table = 'lipa_kidogo_installments';

    protected $fillable = [
        'lipa_kidogo_id',
        'user_id',
        'material_id',
        'installment_number',
        'amount',
        'due_date',
        'status',
        'paid_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    // Relationships
    public function lipaKidogo()
    {
        return $this->belongsTo(LipaKidogo::class, 'lipa_kidogo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Helper methods
    public function isOverdue()
    {
        return $this->status === 'overdue' || 
               ($this->status === 'pending' && Carbon::now()->gt($this->due_date));
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function markAsPaid($paidDate = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => $paidDate ?? now(),
        ]);

        // Check if all installments are paid to mark the main plan as completed
        $this->checkAndCompletePlan();
    }

    public function markAsOverdue()
    {
        if ($this->status === 'pending') {
            $this->update(['status' => 'overdue']);
        }
    }

    private function checkAndCompletePlan()
    {
        $pendingInstallments = $this->lipaKidogo->installments()
            ->where('status', '!=', 'paid')
            ->count();

        if ($pendingInstallments === 0) {
            $this->lipaKidogo->update(['status' => 'completed']);
        }
    }

    // Scopes
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function ($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeDueSoon($query, $days = 3)
    {
        return $query->where('status', 'pending')
                    ->whereBetween('due_date', [now(), now()->addDays($days)]);
    }
}