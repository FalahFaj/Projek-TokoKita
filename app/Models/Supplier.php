<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'tax_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function puchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
     public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDenganJumlahProduk($query)
    {
        return $query->withCount('products');
    }

    public function getProdukDenganAtrJumlah():int
    {
        return $this->products()->count();
    }

    public function getTotalAtrPurchaseOrders(): float
    {
        return $this->puchaseOrders()->where('status', 'received')->sum('total_amount');;
    }

    // Static methods for dashboard

    public static function totalCount(): int
    {
        return self::count();
    }

}
