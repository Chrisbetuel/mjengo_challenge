<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LipaKidogo extends Model
{
    use HasFactory;

    protected $table = 'lipa_kidogo';

    protected $fillable = [
        'user_id',
        'material_id',
        'total_amount',
        'installment_amount',
        'num_installments',
        'start_date',
        'user_type',
        'payment_duration',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'start_date' => 'date',
        'next_payment_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function installments()
    {
        return $this->hasMany(LipaKidogoInstallment::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getPaidInstallments()
    {
        return $this->installments()->where('status', 'paid')->count();
    }

    public function getTotalPaid()
    {
        return $this->installments()->where('status', 'paid')->sum('amount');
    }
}
