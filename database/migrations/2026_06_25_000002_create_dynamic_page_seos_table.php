<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dynamic_page_seos', function (Blueprint $table) {
            $table->id();
            $table->string('page_name', 100)->unique();
            $table->text('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('og_image_name')->nullable();
            $table->text('og_image_path')->nullable();
            $table->decimal('seo_rating', 2, 1)->nullable();
            $table->integer('review_number')->nullable();
            $table->decimal('best_rating', 2, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_page_seos');
    }
};
