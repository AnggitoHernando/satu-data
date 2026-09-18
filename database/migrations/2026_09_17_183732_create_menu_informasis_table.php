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
        Schema::create('menu_informasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menu_informasi')
                ->cascadeOnDelete();

            $table->string('nama_menu');
            $table->string('slug')->unique();
            $table->integer('urutan')->default(0);

            $table->enum('tipe', ['halaman_statis', 'daftar_informasi'])
                ->default('daftar_informasi');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_informasi');
    }
};
