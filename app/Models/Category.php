<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'image_path', 'icon',
        'sort_order', 'is_active', 'is_featured', 'seo_title', 'meta_description',
        'banner_heading', 'banner_subheading', 'banner_image',
        'banner_cta_text', 'banner_cta_url', 'banner_bg_color',
        'brand_logo', 'brand_name', 'sections',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sections' => 'array',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function activeChildren()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /** All descendant ids (children + grandchildren) — used for product scoping. */
    public function descendantIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->descendantIds());
        }

        return $ids;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function publishedProducts()
    {
        return $this->hasMany(Product::class)
            ->where('status', 'active')
            ->whereNotNull('published_at');
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id')->orderBy('sort_order');
    }
}
