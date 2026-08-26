<?php

if (! function_exists('setting')) {
    /** Cached settings getter: setting('delivery.free_above', 999) */
    function setting(string $key, $default = null)
    {
        return app(\App\Services\SettingsService::class)->get($key, $default);
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
