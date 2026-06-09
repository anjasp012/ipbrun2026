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
        'status',
        'tab',
    ];
}
