<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'order_index'];

    // Match the Node backend, which always coerced these to sane defaults
    // (order_index → 0, description → '') rather than null.
    protected $attributes = [
        'order_index' => 0,
        'description' => '',
    ];

    protected function casts(): array
    {
        return [
            'order_index' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if ($category->isDirty('name') || empty($category->slug)) {
                $category->slug = static::slugify($category->name);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public static function slugify(string $value): string
    {
        return Str::slug($value);
    }

    /**
     * Resolve a category id from either a numeric id or a name/slug.
     * Returns null if nothing matches.
     */
    public static function resolveId(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $byId = static::find((int) $value);
            if ($byId) {
                return $byId->id;
            }
        }

        $byName = static::where('name', $value)
            ->orWhere('slug', static::slugify((string) $value))
            ->first();

        return $byName?->id;
    }
}
