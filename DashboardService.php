<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Elevator extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'building_id', 'label', 'brand', 'model', 'serial_number',
        'capacity_kg', 'floors', 'installation_date', 'status', 'notes',
    ];

    protected $casts = [
        'installation_date' => 'date',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    // اختصار للوصول للعميل عبر المبنى
    public function customer()
    {
        return $this->building->customer;
    }
}
