<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ServiceContent extends Model
{
    protected $fillable = [
        'service_id',
        'parent_id',
        'title',
        'slug',
        'description',
        'thumbnail_name',
        'thumbnail_path',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ServiceContent $content) {
            if ($content->isDirty('title') || empty($content->slug)) {
                $content->slug = Str::slug($content->title);
            }
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ServiceContent::class, 'parent_id');
    }

    public function childContents(): HasMany
    {
        return $this->hasMany(ServiceContent::class, 'parent_id')->orderBy('position');
    }
}
