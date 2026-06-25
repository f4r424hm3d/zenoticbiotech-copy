<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicPageSeo extends Model
{
    protected $fillable = [
        'page_name',
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
            'seo_rating' => 'decimal:1',
            'best_rating' => 'decimal:1',
            'review_number' => 'integer',
        ];
    }
}
