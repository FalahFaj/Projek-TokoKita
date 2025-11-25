<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDenganJumlahProduk($query)
    {
        return $query->withCount('produk');
    }

    public function getProdukDenganAtrJumlah():int
    {
        return $this->products()->count();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori){
            if (empty($kategori->slug)) {
                $kategori->slug = \Str::slug($kategori->name);
            }
        });
    }

    // Static methods for dashboard

    public static function totalCount(): int
    {
        return self::count();
    }
}
