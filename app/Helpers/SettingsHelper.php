<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    /**
     * Cache key untuk semua settings
     */
    protected const CACHE_KEY = 'app_settings';

    /**
     * Cache duration dalam menit
     */
    protected const CACHE_DURATION = 60;

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        // Get all settings from cache
        $settings = self::getAllSettings();

        // Split the key into group and setting key
        $keys = explode('.', $key);

        // If no group provided, assume it's in 'general' group
        if (count($keys) === 1) {
            $group = 'general';
            $settingKey = $keys[0];
        } else {
            $group = $keys[0];
            $settingKey = $keys[1];
        }

        // Return the value if exists, otherwise return default
        return $settings[$group][$settingKey] ?? $default;
    }

    /**
     * Get all settings
     */
    public static function getAllSettings(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return Setting::all()
                ->groupBy('group')
                ->map(function ($group) {
                    return $group->pluck('value', 'key');
                })
                ->toArray();
        });
    }

    /**
     * Get all settings in a specific group
     */
    public static function getGroup(string $group): array
    {
        $settings = self::getAllSettings();
        return $settings[$group] ?? [];
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value): void
    {
        // Split the key into group and setting key
        $keys = explode('.', $key);

        if (count($keys) === 1) {
            $group = 'general';
            $settingKey = $keys[0];
        } else {
            $group = $keys[0];
            $settingKey = $keys[1];
        }

        // Update or create the setting
        Setting::updateOrCreate(
            ['group' => $group, 'key' => $settingKey],
            [
                'value' => $value,
                'type' => self::determineType($value),
                'display_name' => ucwords(str_replace('_', ' ', $settingKey))
            ]
        );

        // Clear the cache
        self::clearCache();
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Determine the type of a value
     */
    protected static function determineType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_numeric($value)) {
            return 'number';
        }

        if (is_array($value) || is_object($value)) {
            return 'json';
        }

        return 'text';
    }

    /**
     * Check if a setting exists
     */
    public static function has(string $key): bool
    {
        $settings = self::getAllSettings();

        $keys = explode('.', $key);
        if (count($keys) === 1) {
            return isset($settings['general'][$keys[0]]);
        }

        return isset($settings[$keys[0]][$keys[1]]);
    }
}
