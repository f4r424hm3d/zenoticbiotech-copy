<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'details',
        'icon',
        'emoji',
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
            'order_index' => 'integer',
            'seo_rating' => 'decimal:1',
            'best_rating' => 'decimal:1',
            'review_number' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            if ($service->isDirty('name') || empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(ServiceContent::class)->orderBy('position');
    }

    public function parentContents(): HasMany
    {
        return $this->hasMany(ServiceContent::class)->whereNull('parent_id')->orderBy('position');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ServiceFaq::class)->latest();
    }
}
