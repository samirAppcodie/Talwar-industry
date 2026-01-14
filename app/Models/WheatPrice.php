<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WheatPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_per_kg',
        'price_type',
        'active',
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
        'active' => 'boolean',
    ];

    public static function getPricesByType($type)
    {
        return self::where('price_type', $type)->where('active', true)->pluck('price_per_kg', 'id');
    }
}
