<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'description',
        'category_id',
        'supplier_id',
        'purchase_price',
        'selling_price',
        'profit_margin',
        'stock',
        'min_stock',
        'max_stock',
        'unit',
        'image',
        'is_active',
        'is_available',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
    ];

    protected $appends = [
        'is_low_stock',
        'profit_amount',
        'formatted_selling_price',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    public function scopeStokHabis($query)
    {
        return $query->where('stock','<=', 0);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%");
        });
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function getProfitAmountAttribute(): float
    {
        return $this->selling_price - $this->purchase_price;
    }

    public function getFormattedSellingPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->selling_price, 0, ',', '.');
    }

    public function getInitialsAttribute(): string
    {
    $words = explode(' ', $this->name);
    $initials = '';

    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
        }

    return substr($initials, 0, 2);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->is_low_stock) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getRoleNameAttribute(): string
    {
    return match($this->role) {
        'owner' => 'Owner',
        'admin' => 'Administrator',
        'kasir' => 'Kasir',
        default => 'User'
        };
    }

    public function setSellingPriceAttribute($value)
    {
        $this->attributes['selling_price'] = $value;

        if ($this->purchase_price > 0) {
            $profit = $value - $this->purchase_price;
            $this->attributes['profit_margin'] = ($profit / $this->purchase_price) * 100;
        }
    }

    // Method

    public function tambahStock(int $jumlah, string $note = ''): void
    {
        $oldStock = $this->stock;
        $this->increment('stock', $jumlah);

        StockHistory::create([
            'product_id' => $this->id,
            'type' => 'in',
            'quantity' => $jumlah,
            'old_stock' => $oldStock,
            'new_stock' => $this->stock,
            'note' => $note,
        ]);
    }

    public function kurangiStock(int $jumlah, string $note = ''): bool
    {
        if ($this->stock < $jumlah) {
            return false;
        }

        $oldStock = $this->stock;
        $this->decrement('stock', $jumlah);

        StockHistory::create([
            'product_id' => $this->id,
            'type' => 'out',
            'quantity' => $jumlah,
            'old_stock' => $oldStock,
            'new_stock' => $this->stock,
            'note' => $note,
        ]);

        return true;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . time() . rand(100, 999);
            }
            if (empty($product->barcode)) {
                $product->barcode = 'BC-' . time() . rand(100, 999);
            }
        });
    }

    public static function totalCount(): int
    {
        return self::count();
    }

    public static function lowStockCount(): int
    {
        return self::lowStock()->count();
    }

    public static function outOfStockCount(): int
    {
        // Menggunakan scope stokHabis yang sudah ada (stock <= 0)
        return self::stokHabis()->count();
    }
}
