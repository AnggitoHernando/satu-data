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
            $table->foreignId("group_kategori_item_id")->constrained("group_kategori_items")->cascadeOnDelete();
            $table->double("value", 15, 2)->default(0);
            $table->integer("tahun");
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
