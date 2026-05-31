<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'is_active', 'customer_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ===== فحص الأدوار =====
    public function isAdmin(): bool       { return $this->role === 'admin'; }
    public function isSupervisor(): bool  { return $this->role === 'supervisor'; }
    public function isTechnician(): bool  { return $this->role === 'technician'; }
    public function isCustomer(): bool    { return $this->role === 'customer'; }

    // المدير والمشرفون هم من يحق لهم استقبال الطلبات وتعيين الفنيين
    public function canManageRequests(): bool
    {
        return in_array($this->role, ['admin', 'supervisor']);
    }

    // ===== العلاقات =====

    // حساب العميل المرتبط (لمستخدمي البوابة)
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // الطلبات المسندة لهذا الفني
    public function assignedRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class, 'assigned_technician_id');
    }
}
