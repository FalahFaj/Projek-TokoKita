<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'received_quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'received_quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected $appends = [
        'formatted_unit_price',
        'formatted_subtotal',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return 'Rp ' . number_format((float)$this->unit_price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format((float)$this->subtotal, 0, ',', '.');
    }

    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->unit_price->multipliedBy($this->quantity);
    }

    public function receive(int $quantity): bool
    {
        if ($quantity > ($this->quantity - $this->received_quantity)) {
            return false;
        }

        $this->increment('received_quantity', $quantity);
        return true;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->calculateSubtotal();
        });

        static::updating(function ($item) {
            $item->calculateSubtotal();
        });
    }
}
