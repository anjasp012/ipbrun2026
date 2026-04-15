<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function voucherUsage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VoucherUsage::class);
    }

    public function raceEntries(): HasMany
    {
        return $this->hasMany(RaceEntry::class);
    }

    /**
     * Kode voucher yang dipakai, diambil dari relasi voucher_usages → vouchers.
     */
    public function getVoucherCodeAttribute(): ?string
    {
        return $this->voucherUsage?->voucher?->code;
    }

    /**
     * Jumlah diskon: selisih antara harga asli (tiket + biaya) dan total_price yang sudah terdiskon.
     */
    public function getDiscountAmountAttribute(): int
    {
        $original = $this->raceEntries->sum(fn($e) => $e->ticket->price ?? 0)
            + ($this->admin_fee ?? 0)
            + ($this->donation_event ?? 0)
            + ($this->donation_scholarship ?? 0);

        return max(0, $original - $this->total_price);
    }
}
