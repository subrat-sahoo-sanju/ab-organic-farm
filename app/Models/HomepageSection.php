<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = ['key', 'title', 'subtitle', 'is_visible', 'sort_order', 'config'];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'config' => 'array',
        ];
    }
}
