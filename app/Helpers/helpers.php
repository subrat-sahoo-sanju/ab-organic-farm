<?php

if (! function_exists('setting')) {
    /** Cached settings getter: setting('delivery.free_above', 999) */
    function setting(string $key, $default = null)
    {
        return app(\App\Services\SettingsService::class)->get($key, $default);
    }
}

if (! function_exists('setting_json')) {
    /** Get a JSON setting decoded to an array, falling back to a default. */
    function setting_json(string $key, array $default = [])
    {
        $raw = setting($key, null);
        if ($raw === null || $raw === '') {
            return $default;
        }

        $decoded = json_decode((string) $raw, true);

        return is_array($decoded) ? $decoded : $default;
    }
}

if (! function_exists('money')) {
    /** ₹1,249.00 — consistent INR formatting everywhere. */
    function money($amount, bool $withDecimals = false): string
    {
        $prefix = '₹';

        return $prefix.number_format((float) $amount, $withDecimals ? 2 : 0);
    }
}
