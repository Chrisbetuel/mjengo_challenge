<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'amount',
        'status',
        'payment_date',
        'paid_at',
        'payment_method',
        'transaction_id',
        'selcom_order_id',
        'selcom_trans_id',
        'payment_type', // 'direct' or 'lipa_kidogo'
        'installment_number',
        'total_installments',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Participant::class, 'id', 'id', 'participant_id', 'user_id');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
