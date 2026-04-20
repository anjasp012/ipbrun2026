<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Voucher extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'used_at' => 'datetime',
        'usage_limit' => 'integer',
        'expired_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function usages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getUsedCountAttribute()
    {
        return $this->usages()
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['pending', 'paid']);
            })
            ->distinct('participant_id')
            ->count('participant_id');
    }

    public function isAvailable()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expired_at && now()->isAfter($this->expired_at)) {
            return false;
        }

        if ($this->usage_limit !== null) {
            return $this->used_count < $this->usage_limit;
        }

        return true;
    }

    public function participants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Participant::class, 'voucher_usages')
            ->withPivot('order_id')
            ->withTimestamps();
    }

    public static function findValid($code)
    {
        return self::where('code', $code)
            ->first();
    }


    public function calculateDiscount($originalPrice)
    {
        if ($this->type === 'nominal') {
            return min($this->value, $originalPrice);
        } elseif ($this->type === 'percentage') {
            return floor($originalPrice * ($this->value / 100));
        }

        return 0;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
