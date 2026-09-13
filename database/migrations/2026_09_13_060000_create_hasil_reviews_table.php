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
        Schema::create('hasil_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkj_dokumen_id')->constrained('lkj_dokumens')->cascadeOnDelete();
            $table->foreignId('rubrik_id')->constrained('rubrik_reviews')->cascadeOnDelete();
            $table->foreignId('indikator_kinerja_id')->nullable()->constrained('indikator_kinerjas')->cascadeOnDelete();
            $table->text('uraian_hasil_review')->nullable();
            $table->enum('status', ['Sesuai', 'Belum Sesuai'])->nullable();
            $table->text('catatan_perbaikan')->nullable();
            $table->text('tanggapan_perbaikan_satker')->nullable();
            $table->foreignId('direview_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(
                ['lkj_dokumen_id', 'rubrik_id', 'indikator_kinerja_id'],
                'hasil_reviews_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_reviews');
    }
};
