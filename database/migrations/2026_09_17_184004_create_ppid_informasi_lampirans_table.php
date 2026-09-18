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
        Schema::create('ppid_informasi_lampiran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ppid_informasi_id')
                ->constrained('ppid_informasi')
                ->cascadeOnDelete();

            $table->string('nama_file');            // label tombol VIEW, mis. "Perjanjian Kinerja 2026"
            $table->string('file_path')->nullable(); // path file
            $table->string('tipe_file', 20)->nullable(); // pdf, docx, xlsx
            $table->unsignedBigInteger('ukuran_file')->nullable(); // bytes
            $table->integer('urutan')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppid_informasi_lampiran');
    }
};
