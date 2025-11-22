<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image',
        'status',
        'created_by',
        'stock_quantity',
        'min_order_quantity',
        'max_order_quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_order_quantity' => 'integer',
        'max_order_quantity' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function directPurchases()
    {
        return $this->hasMany(DirectPurchase::class);
    }

    public function lipaKidogoPlans()
    {
        return $this->hasMany(LipaKidogo::class);
    }
}
