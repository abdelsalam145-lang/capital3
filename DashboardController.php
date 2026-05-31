<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'customer_id', 'type', 'value', 'billing_cycle',
        'visits_per_cycle', 'start_date', 'end_date', 'status',
        'auto_renew', 'terms', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'auto_renew' => 'boolean',
        'value'      => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Contract $c) {
            if (empty($c->reference)) {
                $year = now()->year;
                $count = static::whereYear('created_at', $year)->count() + 1;
                $c->reference = sprintf('CON-%d-%04d', $year, $count);
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function elevators(): BelongsToMany
    {
        return $this->belongsToMany(Elevator::class, 'contract_elevator')->withTimestamps();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->start_date->lte(now())
            && $this->end_date->gte(now());
    }

    // عدد الأشهر في دورة الفوترة الواحدة (لتوليد الفواتير الدورية)
    public function cycleMonths(): int
    {
        return match ($this->billing_cycle) {
            'monthly'     => 1,
            'quarterly'   => 3,
            'semi_annual' => 6,
            'annual'      => 12,
        };
    }
}
