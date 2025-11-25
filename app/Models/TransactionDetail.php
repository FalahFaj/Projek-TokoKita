<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Decimal;
use Symfony\Component\CssSelector\Node\FunctionNode;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'product_name',
        'product_sku',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    protected $appends = [
        'formatted_unit_price',
        'formatted_subtotal',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(related: Transaction::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedUnitPriceAtr(): string
    {
        return 'Rp ' . number_format((float) $this->unit_price, 0, ',', '.');
    }

    public function getFormattedSubtotalAtr(): string
    {
        return 'Rp ' . number_format((float) $this->subtotal, 0, ',', '.');
    }

    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->unit_price->multipliedBy($this->quantity);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detail) {
            // Snapshot product info
            if ($detail->product) {
                $detail->product_name = $detail->product->name;
                $detail->product_sku = $detail->product->sku;
            }

            $detail->calculateSubtotal();
        });

        static::updating(function ($detail) {
            $detail->calculateSubtotal();
        });

        static::updating(function ($detail) {
            $detail->calculateSubtotal();
        });
    }

    /**
     * Get the casts for the model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return $this->casts;
    }
}
