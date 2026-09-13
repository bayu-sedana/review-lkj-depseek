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
        Schema::create('lkj_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_reviews')->cascadeOnDelete();
            $table->foreignId('satker_id')->constrained('satkers')->cascadeOnDelete();
            $table->enum('status_keseluruhan', ['belum_upload', 'proses_review', 'perlu_revisi', 'selesai'])
                ->default('belum_upload');
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->unique(['periode_id', 'satker_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkj_submissions');
    }
};
