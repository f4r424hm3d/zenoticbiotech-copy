<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_files', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->text('file_name');
            $table->text('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_files');
    }
};
