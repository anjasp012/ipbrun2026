<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'qty', 'price', 'display', 'category_id', 'period_id', 'type'])]
class Ticket extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'qty',
        'price',
        'display',
        'category_id',
        'period_id',
        'type',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function raceEntries()
    {
        return $this->hasMany(RaceEntry::class);
    }
}
