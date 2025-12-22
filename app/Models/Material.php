<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Material Model
 *
 * Represents building materials available for purchase in the system.
 * Supports both direct purchase and installment (Lipa Kidogo) payment options.
 *
 * @property int $id
 * @property string $name Material name
 * @property string|null $sw_name Material name in Swahili
 * @property string|null $description Material description
 * @property string|null $sw_description Material description in Swahili
 * @property float $price Material price
 * @property string|null $image Material image path
 * @property string $status Material status (active/inactive)
 * @property int $created_by User ID who created the material
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DirectPurchase[] $directPurchases
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LipaKidogo[] $lipaKidogoPlans
 * @property-read bool $is_available
 */
class Material extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sw_name',
        'description',
        'sw_description',
        'price',
        'image',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Status constants for materials.
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Get the user who created this material.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the direct purchases for this material.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function directPurchases()
    {
        return $this->hasMany(DirectPurchase::class);
    }

    /**
     * Get the Lipa Kidogo installment plans for this material.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lipaKidogoPlans()
    {
        return $this->hasMany(LipaKidogo::class);
    }

    /**
     * Check if the material is available for purchase.
     *
     * @return bool
     */
    public function getIsAvailableAttribute()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Scope a query to only include active materials.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include inactive materials.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }
}
