<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'display_name',
        'description'
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)
            ->orWhere(function ($query) use ($key) {
                if (str_contains($key, '.')) {
                    [$group, $key] = explode('.', $key, 2);
                    $query->where('group', $group)->where('key', $key);
                }
            })
            ->first();

        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        $group = 'general';
        $settingKey = $key;

        if (str_contains($key, '.')) {
            [$group, $settingKey] = explode('.', $key, 2);
        }

        static::updateOrCreate(
            [
                'group' => $group,
                'key' => $settingKey,
            ],
            [
                'value' => $value,
                'type' => static::determineType($value),
            ]
        );

        Cache::forget('settings');
    }

    protected static function determineType($value): string
    {
        return match(true) {
            is_bool($value) => 'boolean',
            is_numeric($value) => 'number',
            is_array($value) => 'json',
            default => 'text',
        };
    }
}
