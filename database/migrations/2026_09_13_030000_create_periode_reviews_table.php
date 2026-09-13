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
        Schema::create('periode_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun_lkj');
            $table->unsignedSmallInteger('tahun_review');
            $table->date('deadline_revisi')->nullable();
            $table->enum('status', ['aktif', 'ditutup'])->default('aktif');
            $table->timestamps();

            $table->unique(['tahun_lkj', 'tahun_review']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_reviews');
    }
};
