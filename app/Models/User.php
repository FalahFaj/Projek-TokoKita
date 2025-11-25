<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id' );
    }

    public function transakasiCustomer()
    {
        return $this->hasMany(Transaction::class, 'customer_id' );
    }

    public function riwayatStok()
    {
        return $this->hasMany(StockHistory::class, 'user_id' );
    }

    public function purchaseOrder()
    {
        return $this->hasMany(PurchaseOrder::class, 'user_id' );
    }

    public function scopeAdmins($query)
    {
        $query->whereIn('role', ['admin', 'owner']);
    }

    public function scopeKasirs($query)
    {
        $query->where('role', 'kasir');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    public function hakAkesaAdminPanel(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function isLowStockProducts()
    {
    if (!$this->hakAkesaAdminPanel()) {
        return collect();
        }

    return \App\Models\Product::lowStock()->get();
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

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return substr($initials, 0, 2);
    }

    // Static methods for dashboard

    public static function totalCount(): int
    {
        return self::count();
    }
}
