<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrindingCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'charge_per_kg',
        'customer_type',
        'active',
    ];

    protected $casts = [
        'charge_per_kg' => 'decimal:2',
        'active' => 'boolean',
    ];

    public static function getChargesByType($type)
    {
        return self::where('customer_type', $type)->where('active', true)->pluck('charge_per_kg', 'id');
    }
}
