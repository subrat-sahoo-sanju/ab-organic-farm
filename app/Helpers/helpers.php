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

if (! function_exists('nav_icon_src')) {
    /** Data-URI for public/images/nav/{$name}.svg (never a separate HTTP request,
     *  immune to missing files / ModSecurity), falling back to the asset URL. */
    function nav_icon_src(string $name): string
    {
        $path = public_path('images/nav/'.trim($name, '/'));
        if (is_file($path)) {
            $raw = @file_get_contents($path);
            if ($raw !== false && trim($raw) !== '') {
                return 'data:image/svg+xml;base64,'.base64_encode($raw);
            }
        }

        return asset('images/nav/'.$name.'.svg');
    }
}
