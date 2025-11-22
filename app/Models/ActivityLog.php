<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logMaterialPurchase($userId, $materialId, $amount, $type)
    {
        return self::create([
            'user_id' => $userId,
            'action' => 'material_purchase',
            'description' => "Material purchase - Type: {$type}, Amount: {$amount}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => [
                'material_id' => $materialId,
                'amount' => $amount,
                'type' => $type,
            ],
        ]);
    }
}
