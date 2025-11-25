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

    public static function logChallengeJoin($userId, $challengeId)
    {
        return self::create([
            'user_id' => $userId,
            'action' => 'challenge_join',
            'description' => "User joined challenge ID: {$challengeId}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => [
                'challenge_id' => $challengeId,
            ],
        ]);
    }

    public static function logPayment($userId, $amount, $challengeId)
    {
        return self::create([
            'user_id' => $userId,
            'action' => 'payment',
            'description' => "Payment of amount: {$amount} for challenge ID: {$challengeId}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => [
                'amount' => $amount,
                'challenge_id' => $challengeId,
            ],
        ]);
    }
}
