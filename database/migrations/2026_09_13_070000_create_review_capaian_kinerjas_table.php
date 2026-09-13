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
        Schema::create('review_capaian_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkj_dokumen_id')->constrained('lkj_dokumens')->cascadeOnDelete();
            $table->foreignId('indikator_kinerja_id')->constrained('indikator_kinerjas')->cascadeOnDelete();
            $table->decimal('nilai_exec_summary', 20, 4)->nullable();
            $table->decimal('nilai_bab_3', 20, 4)->nullable();
            $table->decimal('nilai_bab_4', 20, 4)->nullable();
            $table->decimal('nilai_aplikasi_kinerjaku', 20, 4)->nullable();
            $table->decimal('nilai_data_dukung', 20, 4)->nullable();
            $table->boolean('is_sinkron')->default(false);
            $table->text('catatan_perbaikan')->nullable();
            $table->text('tanggapan_perbaikan_satker')->nullable();
            $table->foreignId('direview_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(
                ['lkj_dokumen_id', 'indikator_kinerja_id'],
                'review_capaian_kinerjas_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_capaian_kinerjas');
    }
};
