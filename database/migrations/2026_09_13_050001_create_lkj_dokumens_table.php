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
        Schema::create('lkj_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkj_submission_id')->constrained('lkj_submissions')->cascadeOnDelete();
            $table->unsignedInteger('versi')->default(1);
            $table->string('file_path');
            $table->string('file_name');
            $table->foreignId('diupload_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['lkj_submission_id', 'versi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkj_dokumens');
    }
};
