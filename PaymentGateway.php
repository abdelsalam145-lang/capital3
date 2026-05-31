<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Part extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku', 'name', 'brand', 'category', 'unit',
        'cost_price', 'sale_price', 'quantity_on_hand',
        'reorder_level', 'notes', 'is_active',
    ];

    protected $casts = [
        'cost_price'       => 'decimal:2',
        'sale_price'       => 'decimal:2',
        'quantity_on_hand' => 'decimal:2',
        'reorder_level'    => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(PartStock::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'part_stocks')
                    ->withPivot('quantity')->withTimestamps();
    }

    /** هل وصلت القطعة لحد إعادة الطلب؟ */
    public function isLowStock(): bool
    {
        return $this->quantity_on_hand <= $this->reorder_level;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity_on_hand', '<=', 'reorder_level')
                     ->where('is_active', true);
    }
}
