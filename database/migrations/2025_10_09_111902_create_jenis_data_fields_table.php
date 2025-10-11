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
        Schema::create('jenis_data_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId("jenis_data_id")->constrained("jenis_data")->onUpdate("cascade")->onDelete("cascade");
            $table->string("nama_field", 100);
            $table->string("jenis_data", 50);
            $table->text("keterangan");
            $table->integer("urutan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_data_fields');
    }
};
