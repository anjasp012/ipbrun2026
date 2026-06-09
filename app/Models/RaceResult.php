<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaceResult extends Model
{
    protected $fillable = [
        'item',
        'bib',
        'name',
        'gender',
        'gun_time',
        'net_time',
        'start_time',
        'cp1',
        'cp2',
        'cp_3km',       // 3KM  — FM, HM, 10K, 5K
        'cp_6_4km',     // 6.4KM — HM
        'cp_8_9km',     // 8.9KM — FM, HM
        'cp_10km',      // 10KM  — 10K / FM split
        'cp_16_1km',    // 16.1KM — FM, HM
        'cp_19km',      // 19KM  — FM, HM
        'cp_26_1km',    // 26.1KM — FM
        'cp_29km',      // 29KM  — FM
        'cp_36km',      // 36KM  — FM
        'cp_38_5km',    // 38.5KM — FM
        'status',
        'tab',
    ];
}
