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
        Schema::create('rubrik_reviews', function (Blueprint $table) {
            $table->id();
            $table->enum('aspek', ['format_pelaporan', 'pengungkapan_informasi']);
            $table->enum('tipe_evaluasi', ['per_dokumen', 'per_indikator']);
            $table->string('bagian_laporan');
            $table->text('minimum_informasi');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrik_reviews');
    }
};
