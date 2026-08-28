<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'placement', 'title', 'subtitle',
        'desktop_image', 'mobile_image', 'button_text', 'button_url',
        'width', 'height',
        'starts_at', 'ends_at', 'sort_order', 'is_active', 'show_text',
    ];

    /**
     * Recommended dimensions (width, height) for a placement.
     * Admins can override per banner in the form.
     */
    public static function recommendedDimensions(string $placement): array
    {
        return match ($placement) {
            'hero'            => [1400, 400],
            'strip'           => [1200, 150],
            'category_top'    => [1200, 220],
            'promotional'     => [1200, 400],
            default           => [1200, 400],
        };
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
            'show_text' => 'boolean',
        ];
    }

    public function isRunning(): bool
    {
        $now = now();

        return $this->is_active
            && (! $this->starts_at || $this->starts_at->lte($now))
            && (! $this->ends_at || $this->ends_at->gte($now));
    }

    public function scopeRunning($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order');
    }
}
