<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    use HasFactory;

    protected $casts = [
        'details' => 'json',
    ];

    public function prices() {
        return $this->hasMany(ProductPrice::class);
    }

    public function store() {
        return $this->belongsTo(Store::class);
    }
}
