<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'category_id', 'brand_id', 'name', 'slug', 'sku',
        'short_description', 'description',
        'ingredients', 'benefits', 'usage_instructions', 'storage_instructions',
        'origin', 'farmer_source', 'certification', 'is_organic', 'badge_label',
        'cost_price', 'regular_price', 'sale_price',
        'weight_grams', 'unit_label', 'promo_note',
        'status', 'is_featured', 'is_best_seller', 'is_new_arrival',
        'seo_title', 'meta_description', 'meta_keywords', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_organic' => 'boolean',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
            'cost_price' => 'decimal:2',
            'regular_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'published_at' => 'datetime',
        ];
    }

    /* ---------------- Helpers ---------------- */

    /** Second image for hover-swap on cards, falls back to primary. */
    public function hoverImage()
    {
        return $this->images()
            ->when(true, fn ($q) => $q)
            ->orderBy('sort_order')
            ->skip(1)
            ->first() ?? $this->primaryImage;
    }

    /** Display badge for product cards — admin override first. */
    public function displayBadge(): ?string
    {
        if (! empty($this->badge_label)) {
            return $this->badge_label;
        }

        if ($this->is_new_arrival) {
            return 'New Launch';
        }

        if ($this->is_best_seller) {
            return 'Best Seller';
        }

        return null;
    }

    /* ---------------- Relationships ---------------- */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)
            ->where('is_default', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->latest();
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(self::class, 'related_products', 'product_id', 'related_product_id')
            ->withPivot(['type', 'score'])
            ->wherePivot('type', 'similar')
            ->orderByPivot('score', 'desc');
    }

    public function boughtTogether()
    {
        return $this->belongsToMany(self::class, 'related_products', 'product_id', 'related_product_id')
            ->withPivot(['type', 'score'])
            ->wherePivot('type', 'bought_together')
            ->orderByPivot('score', 'desc');
    }

    /* ---------------- Scopes ---------------- */

    public function scopePublished($query): Builder
    {
        return $query->where('status', 'active')->whereNotNull('published_at');
    }

    public function scopeInCategoryTree($query, Category $category): Builder
    {
        return $query->whereIn('category_id', $category->descendantIds());
    }

    /* ---------------- Helpers ---------------- */

    /** Fallback price when a product has no variants. */
    public function basePrice(): float
    {
        return (float) ($this->sale_price ?? $this->regular_price);
    }

    public function discountPercent(): int
    {
        $price = (float) $this->regular_price;
        if ($price <= 0 || ! $this->sale_price) {
            return 0;
        }

        return (int) round((($price - (float) $this->sale_price) / $price) * 100);
    }
}
