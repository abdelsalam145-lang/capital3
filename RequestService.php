<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Building extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'name', 'manager_name', 'manager_phone',
        'latitude', 'longitude', 'address', 'notes',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function elevators(): HasMany
    {
        return $this->hasMany(Elevator::class);
    }
}
