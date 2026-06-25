<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('default_og_images', function (Blueprint $table) {
            $table->id();
            $table->string('page', 200)->unique();
            $table->string('file_name', 200);
            $table->text('file_path');
            $table->boolean('default')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_og_images');
    }
};
