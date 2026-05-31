<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'name', 'phone', 'email',
        'latitude', 'longitude', 'address',
        'tax_number', 'national_id', 'notes', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'latitude'   => 'decimal:7',
        'longitude'  => 'decimal:7',
    ];

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    // الوصول لكل المصاعد التابعة للعميل عبر المباني
    public function elevators(): HasManyThrough
    {
        return $this->hasManyThrough(Elevator::class, Building::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // حساب العميل لتسجيل الدخول بالبوابة
    public function userAccount()
    {
        return $this->hasOne(User::class);
    }
}
