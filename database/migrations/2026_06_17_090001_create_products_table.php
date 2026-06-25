<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->index();
            $table->text('description');
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();
            $table->enum('segment', ['human', 'animal'])->default('human')->index();
            $table->string('image_url')->nullable();
            $table->json('features')->nullable();
            $table->enum('status', ['published', 'draft'])->default('draft')->index();
            $table->unsignedInteger('order_index')->default(0)->index();
            $table->timestamps();

            // Common query path: published products of a given segment, ordered.
            $table->index(['status', 'segment', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
