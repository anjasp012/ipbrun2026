<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'qty', 'price', 'display', 'category_id', 'period_id'])]
class Ticket extends Model
{
    use HasUuids;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
