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

    public function voucherUsages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function raceEntries(): HasMany
    {
        return $this->hasMany(RaceEntry::class);
    }

    /**
     * Kode voucher yang dipakai, diambil dari relasi voucher_usages → vouchers.
     * Jika ada lebih dari satu, digabung dengan tanda '+'.
     */
    public function getVoucherCodeAttribute(): ?string
    {
        $codes = $this->voucherUsages->map(fn($u) => $u->voucher->code ?? '')->filter()->toArray();
        return !empty($codes) ? implode(' + ', $codes) : null;
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
