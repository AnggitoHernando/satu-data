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
        Schema::create('halaman_statis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_id')
                ->constrained('menu_informasi')
                ->cascadeOnDelete();

            $table->string('judul');
            $table->longText('isi_konten');
            $table->string('gambar_utama')->nullable();
            $table->string('meta_deskripsi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halaman_statis');
    }
};
