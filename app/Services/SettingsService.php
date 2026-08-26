<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected ?array $cache = null;

    public function get(string $key, $default = null)
    {
        [$group, $name] = $this->split($key);

        return $this->all()[$group.'.'.$name] ?? $default;
    }

    public function set(string $key, $value): void
    {
        [$group, $name] = $this->split($key);

        Setting::updateOrCreate(
            ['group' => $group, 'key' => $name],
            ['value' => is_array($value) ? json_encode($value) : (string) $value]
        );

        Cache::forget('settings.all');
        $this->cache = null;
    }

    public function all(): array
    {
        if ($this->cache === null) {
            $this->cache = Cache::rememberForever('settings.all', fn () => Setting::query()
                ->get()
                ->mapWithKeys(fn ($s) => [$s->group.'.'.$s->key => $s->value])
                ->all());
        }

        return $this->cache;
    }

    protected function split(string $key): array
    {
        return str_contains($key, '.') ? explode('.', $key, 2) : ['general', $key];
    }
}
