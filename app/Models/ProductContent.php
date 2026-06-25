<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductContent extends Model
{
    protected $fillable = [
        'product_id',
        'parent_id',
        'title',
        'slug',
        'description',
        'position',
    ];

    protected function casts(): array
    {
        return ['position' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saving(function (ProductContent $content) {
            if ($content->isDirty('title') || empty($content->slug)) {
                $content->slug = Str::slug($content->title);
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductContent::class, 'parent_id');
    }

    public function childContents(): HasMany
    {
        return $this->hasMany(ProductContent::class, 'parent_id')->orderBy('position');
    }
}
