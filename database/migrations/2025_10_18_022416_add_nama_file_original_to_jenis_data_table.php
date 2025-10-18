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
        Schema::table('jenis_data', function (Blueprint $table) {
            $table->string("nama_original_file")->nullable()->after('file_path');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_data', function (Blueprint $table) {
            $table->dropColumn('nama_original_file');
        });
    }
};
