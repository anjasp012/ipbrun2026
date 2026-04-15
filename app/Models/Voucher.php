<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public static function findValid($code)
    {
        return self::where('code', $code)
            ->whereNull('participant_id')
            ->first();
    }

    public static function findAssigned($participantId)
    {
        return self::where('participant_id', $participantId)->first();
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
}
