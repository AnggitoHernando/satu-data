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
        Schema::create('isi_statistiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("kategori_data_id")->constrained("kategori_data")->onUpdate("cascade")->onDelete("cascade");
            $table->string("tahun", 4);
            $table->string("value");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isi_statistiks');
    }
};
