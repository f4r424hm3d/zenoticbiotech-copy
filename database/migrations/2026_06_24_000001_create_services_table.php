<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('details')->nullable();
            $table->string('icon')->default('circle');
            $table->string('emoji')->nullable();
            $table->enum('status', ['published', 'draft'])->default('published')->index();
            $table->unsignedInteger('order_index')->default(0)->index();
            $table->timestamps();

            $table->index(['status', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
