<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'type', 'technician_id', 'location', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(PartStock::class);
    }
}
