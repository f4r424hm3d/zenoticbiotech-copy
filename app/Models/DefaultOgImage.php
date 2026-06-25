<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DefaultOgImage extends Model
{
    protected $fillable = [
        'page',
        'file_name',
        'file_path',
        'default',
    ];

    protected function casts(): array
    {
        return [
            'default' => 'boolean',
        ];
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('page', 'all');
    }
}
