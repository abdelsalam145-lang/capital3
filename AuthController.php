<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'customer_id', 'contract_id', 'maintenance_request_id',
        'issue_date', 'due_date', 'subtotal', 'tax_rate', 'tax_amount',
        'discount', 'total', 'amount_paid', 'status', 'currency', 'notes',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'due_date'    => 'date',
        'subtotal'    => 'decimal:2',
        'tax_rate'    => 'decimal:2',
        'tax_amount'  => 'decimal:2',
        'discount'    => 'decimal:2',
        'total'       => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invoice $inv) {
            if (empty($inv->reference)) {
                $year = now()->year;
                $count = static::whereYear('created_at', $year)->count() + 1;
                $inv->reference = sprintf('INV-%d-%05d', $year, $count);
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class, 'maintenance_request_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /** المبلغ المتبقّي */
    public function balance(): float
    {
        return round($this->total - $this->amount_paid, 2);
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'partially_paid', 'overdue']);
    }
}
EOF
cat > /home/claude/elevator/app/Models/Invoice.php.tmp << 'EOF'
EOF
rm /home/claude/elevator/app/Models/Invoice.php.tmp 2>/dev/null
echo "Invoice model created"