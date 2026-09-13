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
        Schema::create('penugasan_monev', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_reviews')->cascadeOnDelete();
            $table->foreignId('satker_id')->constrained('satkers')->cascadeOnDelete();
            $table->foreignId('monev_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['periode_id', 'satker_id', 'monev_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_monev');
    }
};
