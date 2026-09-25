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
        Schema::create('permohonan', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_registrasi')->unique();

            $table->enum('jenis', ['informasi', 'keberatan']);

            $table->foreignId('permohonan_asal_id')
                ->nullable()
                ->constrained('permohonan')
                ->nullOnDelete();

            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('pekerjaan')->nullable();
            $table->string('no_telepon');
            $table->text('alamat');
            $table->string('bukti_identitas')->nullable(); // path file KTP/SIM/Passport/KTM

            $table->text('rincian_informasi')->nullable();      // "Rincian Informasi Yang Diminta"
            $table->enum('cara_mendapatkan', ['email_download', 'ambil_langsung', 'pos'])->nullable();

            $table->text('tujuan_penggunaan')->nullable();

            $table->text('alasan_keberatan')->nullable();

            $table->enum('status', [
                'diajukan',      // baru masuk, belum diproses admin
                'diverifikasi',  // data pemohon & kelengkapan sudah dicek admin
                'diproses',      // sedang ditindaklanjuti / ditelaah
                'ditanggapi',    // PPID sudah memberi jawaban/keputusan
                'selesai',       // permohonan tuntas, informasi sudah diterima pemohon
                'ditolak',       // permohonan/keberatan ditolak
            ])->default('diajukan');

            $table->text('tanggapan')->nullable();
            $table->date('tanggal_tanggapan')->nullable();

            $table->timestamps();

            $table->index(['jenis', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan');
    }
};
