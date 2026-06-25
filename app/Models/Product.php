<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'segment',
        'image_url',
        'features',
        'status',
        'order_index',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'og_image_name',
        'og_image_path',
        'seo_rating',
        'review_number',
        'best_rating',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'order_index' => 'integer',
            'seo_rating' => 'decimal:1',
            'best_rating' => 'decimal:1',
            'review_number' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if ($product->isDirty('name') || empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeSegment(Builder $query, ?string $segment): Builder
    {
        return $segment ? $query->where('segment', $segment) : $query;
    }

    public function contents(): HasMany
    {
        return $this->hasMany(ProductContent::class)->orderBy('position');
    }

    public function parentContents(): HasMany
    {
        return $this->hasMany(ProductContent::class)->whereNull('parent_id')->orderBy('position');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class)->latest();
    }
}
