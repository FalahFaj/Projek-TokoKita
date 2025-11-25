<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'order_date',
        'expected_date',
        'status',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    protected $appends = [
        'formatted_total_amount',
        'items_count',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeOrdered($query)
    {
        return $query->where('status', 'ordered');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp ' . number_format((float)$this->total_amount, 0, ',', '.');
    }

    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function calculateTotal(): void
    {
        $this->total_amount = $this->items->sum('subtotal');
    }

    public function markAsOrdered(): void
    {
        $this->update(['status' => 'ordered']);
    }

    public function markAsReceived(): void
    {
        $this->update(['status' => 'received']);

        foreach ($this->items as $item) {
            $item->product->increaseStock(
                $item->received_quantity,
                "Pembelian - PO {$this->po_number}"
            );
        }
    }

    public static function generatePONumber(): string
    {
        $date = now()->format('Ymd');
        $lastPO = self::where('po_number', 'like', "PO-{$date}-%")->latest()->first();

        $sequence = $lastPO ?
            (int) substr($lastPO->po_number, -4) + 1 : 1;

        return "PO-{$date}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchaseOrder) {
            if (empty($purchaseOrder->po_number)) {
                $purchaseOrder->po_number = self::generatePONumber();
            }
        });

        static::updated(function ($purchaseOrder) {
            $purchaseOrder->calculateTotal();
            $purchaseOrder->saveQuietly();
        });
    }
}
