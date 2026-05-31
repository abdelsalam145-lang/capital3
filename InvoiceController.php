<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'reference', 'invoice_id', 'customer_id', 'amount', 'currency',
        'method', 'status', 'gateway', 'gateway_payment_id',
        'gateway_response', 'recorded_by', 'paid_at', 'notes',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Payment $p) {
            if (empty($p->reference)) {
                $year = now()->year;
                $count = static::whereYear('created_at', $year)->count() + 1;
                $p->reference = sprintf('PAY-%d-%05d', $year, $count);
            }
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
