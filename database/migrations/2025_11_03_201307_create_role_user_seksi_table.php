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
            $table->id();

            // relasi ke users
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // relasi ke seksi
            $table->foreignId('seksi_id')
                ->nullable()
                ->constrained('seksi')
                ->nullOnDelete();
            $table->string('role', 70);
            $table->timestamps();
            $table->unique(['user_id', 'seksi_id']);
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
