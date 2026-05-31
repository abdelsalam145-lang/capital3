<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MaintenanceRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'type', 'elevator_id', 'customer_id', 'created_by',
        'assigned_technician_id', 'assigned_by', 'assigned_at',
        'title', 'description', 'priority', 'status',
        'scheduled_at', 'completed_at', 'resolution_notes',
    ];

    protected $casts = [
        'assigned_at'  => 'datetime',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * توليد رقم مرجعي تلقائي عند الإنشاء مثل REQ-2024-00001
     */
    protected static function booted(): void
    {
        static::creating(function (MaintenanceRequest $request) {
            if (empty($request->reference)) {
                $year = now()->year;
                $count = static::whereYear('created_at', $year)->count() + 1;
                $request->reference = sprintf('REQ-%d-%05d', $year, $count);
            }
        });
    }

    // ===== العلاقات =====

    public function elevator(): BelongsTo
    {
        return $this->belongsTo(Elevator::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(RequestStatusLog::class)->latest();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(RequestAttachment::class);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(TechnicianRating::class);
    }

    public function partUsages(): HasMany
    {
        return $this->hasMany(PartUsage::class);
    }

    // ===== مساعدات الحالة =====

    public function isAssigned(): bool
    {
        return ! is_null($this->assigned_technician_id);
    }

    public function isOpen(): bool
    {
        return ! in_array($this->status, ['closed', 'cancelled']);
    }

    // ===== Scopes للاستعلام السريع =====

    public function scopeForTechnician($query, int $technicianId)
    {
        return $query->where('assigned_technician_id', $technicianId);
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', ['closed', 'cancelled']);
    }
}
