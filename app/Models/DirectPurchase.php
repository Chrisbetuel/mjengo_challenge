<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'material_id',
        'quantity',
        'unit_price',
        'total_amount',
        'delivery_address',
        'phone_number',
        'status',
        'payment_reference',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
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

    // Helper methods
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function markAsPaid($paymentReference = null)
    {
        $this->update([
            'status' => 'paid',
            'payment_reference' => $paymentReference,
        ]);
    }

    public function markAsShipped()
    {
        $this->update(['status' => 'shipped']);
    }

    public function markAsDelivered()
    {
        $this->update(['status' => 'delivered']);
    }
}
