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
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkj_submission_id')->constrained('lkj_submissions')->cascadeOnDelete();
            $table->string('file_word_path')->nullable();
            $table->string('file_pdf_path')->nullable();
            $table->foreignId('diupload_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('lkj_submission_id', 'berita_acaras_submission_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};
