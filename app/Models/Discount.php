<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name', 'type', 'value', 'tiers',
        'scope', 'discountable_type', 'discountable_id',
        'max_discount_amount', 'starts_at', 'ends_at',
        'usage_limit', 'usage_count', 'priority', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'tiers' => 'array',
            'max_discount_amount' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function discountable()
    {
        return $this->morphTo();
    }

    /** Currently running? */
    public function isRunning(): bool
    {
        $now = now();

        return $this->is_active
            && (! $this->starts_at || $this->starts_at->lte($now))
            && (! $this->ends_at || $this->ends_at->gte($now));
    }

    /** Best bulk-tier percent for a given quantity. */
    public function tierPercentFor(int $qty): int
    {
        if ($this->type !== 'bulk_tier' || ! $this->tiers) {
            return 0;
        }

        $best = 0;
        foreach ($this->tiers as $tier) {
            if ($qty >= (int) $tier['qty'] && (int) $tier['percent'] > $best) {
                $best = (int) $tier['percent'];
            }
        }

        return $best;
    }
}
