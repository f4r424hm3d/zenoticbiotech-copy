<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->longText('description');
            $table->string('thumbnail_name')->nullable();
            $table->text('thumbnail_path')->nullable();
            $table->unsignedInteger('position')->default(1)->index();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('service_contents')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['service_id', 'parent_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_contents');
    }
};
