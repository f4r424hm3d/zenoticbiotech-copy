<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('meta_title')->nullable()->after('order_index');
            $table->text('meta_keyword')->nullable()->after('meta_title');
            $table->longText('meta_description')->nullable()->after('meta_keyword');
            $table->string('og_image_name')->nullable()->after('meta_description');
            $table->text('og_image_path')->nullable()->after('og_image_name');
            $table->decimal('seo_rating', 2, 1)->nullable()->after('og_image_path');
            $table->integer('review_number')->nullable()->after('seo_rating');
            $table->decimal('best_rating', 2, 1)->nullable()->after('review_number');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_keyword',
                'meta_description',
                'og_image_name',
                'og_image_path',
                'seo_rating',
                'review_number',
                'best_rating',
            ]);
        });
    }
};
