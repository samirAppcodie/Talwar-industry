<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'admin_id',
        'transaction_type',
        'transaction_date',
        'wheat_in_kg',
        'wheat_out_kg',
        'grinding_charges_per_kg',
        'grinding_total_charge',
        'cash_in',
        'cash_out',
        'balance_wheat_after',
        'balance_cash_after',
        'weight_at_entry',
        'weight_at_exit',
        'card_tapped',
        'remarks',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'wheat_in_kg' => 'decimal:2',
        'wheat_out_kg' => 'decimal:2',
        'grinding_charges_per_kg' => 'decimal:2',
        'grinding_total_charge' => 'decimal:2',
        'cash_in' => 'decimal:2',
        'cash_out' => 'decimal:2',
        'balance_wheat_after' => 'decimal:2',
        'balance_cash_after' => 'decimal:2',
        'weight_at_entry' => 'decimal:2',
        'weight_at_exit' => 'decimal:2',
        'card_tapped' => 'boolean',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
