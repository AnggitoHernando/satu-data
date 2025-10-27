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
        Schema::create('jenis_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId("seksi_id")->constrained("seksi")->onUpdate("cascade")->onDelete("cascade");
            $table->string("judul_data");
            $table->string("slug");
            $table->string("deskripsi");
            $table->integer("tahun");
            $table->string("sumber_data");
            $table->enum('status_data', ['private', 'publik'])->default('private');
            $table->enum('status_upload', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->string("error_message_upload")->nullable();
            $table->string("file_path")->nullable();
            $table->string("nama_original_file")->nullable();
            $table->string("extension_file");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_data');
    }
};
