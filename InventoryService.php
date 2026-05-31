<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartUsage extends Model
{
    protected $fillable = [
        'maintenance_request_id', 'part_id', 'warehouse_id', 'used_by',
        'quantity', 'unit_price', 'line_total', 'billed',
    ];

    protected $casts = [
        'quantity'   => 'decimal:2',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'billed'     => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (PartUsage $u) {
            $u->line_total = round($u->quantity * $u->unit_price, 2);
        });
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class, 'maintenance_request_id');
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }
}
