<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    public static function getSetting(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => in_array($setting->value, ['1', 'true', true], true),
            'json' => json_decode((string) $setting->value, true) ?: $default,
            'number' => is_numeric($setting->value) ? $setting->value : $default,
            default => $setting->value,
        };
    }

    public static function setSetting(string $key, mixed $value, string $type = 'text', ?string $description = null): void
    {
        $storedValue = match ($type) {
            'boolean' => $value ? 'true' : 'false',
            'json' => is_string($value) ? $value : json_encode($value),
            default => (string) $value,
        };

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'type' => $type, 'description' => $description]
        );
    }
}
