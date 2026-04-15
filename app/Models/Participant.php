<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RaceEntry;

class Participant extends Model
{
    use HasUuids;

    protected $guarded = [];

    /**
     * Get the first ticket as the primary category (Backward Compatibility)
     */
    public function getTicketAttribute()
    {
        return $this->raceEntries->first()?->ticket;
    }

    public function raceEntries(): HasMany
    {
        return $this->hasMany(RaceEntry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vouchers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Voucher::class, 'voucher_usages')
            ->withPivot('order_id')
            ->withTimestamps();
    }

}
