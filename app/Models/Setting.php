<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    public $timestamps = true;

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? self::castValue($setting->value, $setting->type) : $default;
    }

    public static function setValue($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        $setting = self::firstOrNew(['key' => $key]);

        $setting->value = $value;
        $setting->type = $type;
        $setting->group = $group;
        $setting->description = $description;

        return $setting->save();
    }

    private static function castValue($value, $type)
    {
        return match($type) {
            'integer' => (int) $value,
            'float', 'decimal' => (float) $value,
            'boolean' => (bool) $value,
            'json' => json_decode($value, true),
            'array' => explode(',', $value),
            default => (string) $value
        };
    }

    public static function getStoreName(): string
    {
        return self::getValue('store_name', 'TokoKita');
    }

    public static function getStoreAddress(): string
    {
        return self::getValue('store_address', '');
    }

    public static function getStorePhone(): string
    {
        return self::getValue('store_phone', '');
    }

    public static function getTaxRate(): float
    {
        return self::getValue('tax_rate', 0.11);
    }

    public static function getLowStockThreshold(): int
    {
        return self::getValue('low_stock_threshold', 5);
    }
}
