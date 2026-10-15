<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('galeri_halaman_statis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('halaman_statis_id')
                ->constrained('halaman_statis')
                ->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri_halaman_statis');
    }
};
