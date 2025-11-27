<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'customer_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_status',
        'payment_reference',
        'customer_name',
        'customer_phone',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    protected $appends = [
        'formatted_total_amount',
        'formatted_paid_amount',
        'items_count',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function riwayatStok(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeMingguIni($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(),now()->endOfWeek()]);
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total_amount, 0, ',', '.');
    }

    public function getFormattedPaidAmountAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->paid_amount, 0, ',', '.');
    }

    public function getItemsCountAttribute(): int
    {
        return $this->detailTransaksi()->sum('quantity');
    }

    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'completed'
        ]);
    }

    public function prosesNgurangiStok(): void
    {
        foreach ($this->detailTransaksi as $detail) {
            $detail->produk()->kurangiStock($detail->quantity, "Penjualan - Transaksi {$this->transaction_code}");
        }
    }

    public static function generateTransactionCode(): string
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::where('transaction_code', 'like', "TRX-{$date}-%")->latest()->first();

        $sequence = $lastTransaction ?
            (int) substr($lastTransaction->transaction_code, -4) + 1 : 1;

        return "TRX-{$date}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $transaction->transaction_code = self::generateTransactionCode();
            }
        });

        static::created(function ($transaction) {
            if ($transaction->payment_status === 'paid') {
                $transaction->prosesNgurangiStok();
            }
        });
    }

    // Static methods for dashboard

    public static function todaySalesCount(): int
    {
        return self::today()->paid()->count();
    }

    public static function todayRevenue()
    {
        return self::today()->paid()->sum('total_amount');
    }

    public static function monthlyRevenue()
    {
        return self::bulanIni()->paid()->sum('total_amount');
    }

    public static function totalPaidCount(): int
    {
        return self::paid()->count();
    }
}
