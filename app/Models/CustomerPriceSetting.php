<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPriceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'wheat_price_per_kg',
        'grinding_charge_per_kg',
        'is_wholesale',
    ];

    protected $casts = [
        'wheat_price_per_kg' => 'decimal:2',
        'grinding_charge_per_kg' => 'decimal:2',
        'is_wholesale' => 'string',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
