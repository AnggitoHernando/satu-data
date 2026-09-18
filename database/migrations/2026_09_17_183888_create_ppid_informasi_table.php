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
        Schema::create('ppid_informasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                ->nullable()
                ->constrained('menu_informasi')
                ->nullOnDelete();
            $table->foreignId('jenis_data_id')
                ->nullable()
                ->constrained('jenis_data')
                ->nullOnDelete();
            $table->foreignId('seksi_id')
                ->nullable()
                ->constrained('seksi')
                ->nullOnDelete();
            $table->string('nama_informasi');
            $table->integer("tahun")->nullable();
            $table->text('ringkasan')->nullable();
            $table->string('pejabat_penguasa')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->date('waktu_pembuatan')->nullable();
            $table->date('waktu_penguasaan')->nullable();
            $table->enum('kategori', [
                'berkala',
                'serta_merta',
                'setiap_saat',
                'dikecualikan',
            ])->default('setiap_saat');
            $table->enum('bentuk_dokumen', [
                'soft_copy',
                'hard_copy',
                'keduanya',
            ])->default('soft_copy');
            $table->enum('status', [
                'dapat_diakses',
                'dikecualikan',
            ])->default('dapat_diakses');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->index(['menu_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppid_informasis');
    }
};
