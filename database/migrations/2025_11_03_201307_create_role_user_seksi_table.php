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
        Schema::create('role_user_seksi', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('seksi_id')
                ->constrained('seksi')
                ->onDelete('cascade');

            // timestamps opsional (boleh dihapus kalau tidak mau)
            $table->timestamps();

            // kombinasi unik (primary key gabungan)
            $table->primary(['user_id', 'seksi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user_seksi');
    }
};
