<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Monev\BeritaAcaraController;
use App\Http\Controllers\Monev\HasilReviewController;
use App\Http\Controllers\Monev\ReviewCapaianKinerjaController;
use App\Http\Controllers\Monev\ReviewController as MonevReviewController;
use App\Http\Controllers\PenugasanMonevController;
use App\Http\Controllers\PeriodeReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Satker\IndikatorKinerjaController;
use App\Http\Controllers\Satker\LkjSubmissionController;
use App\Http\Controllers\Satker\RevisiController;
use App\Http\Controllers\Satker\SasaranKegiatanController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('satkers', SatkerController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');
        Route::resource('periodes', PeriodeReviewController::class)
            ->parameters(['periodes' => 'periode'])
            ->except('show');

        Route::get('penugasan', [PenugasanMonevController::class, 'index'])->name('penugasan.index');
        Route::post('penugasan', [PenugasanMonevController::class, 'store'])->name('penugasan.store');
        Route::delete('penugasan/{penugasan}', [PenugasanMonevController::class, 'destroy'])->name('penugasan.destroy');
    });

Route::middleware(['auth', 'role:monev'])
    ->prefix('monev')
    ->name('monev.')
    ->group(function () {
        Route::get('dashboard', [MonevReviewController::class, 'index'])->name('dashboard');
        Route::get('review/{penugasan}', [MonevReviewController::class, 'show'])->name('review.show');
        Route::post('review/{penugasan}/aspek1', [HasilReviewController::class, 'storeAspek1'])->name('review.aspek1');
        Route::post('review/{penugasan}/aspek2', [ReviewCapaianKinerjaController::class, 'storeAspek2'])->name('review.aspek2');
        Route::post('review/{penugasan}/aspek3', [HasilReviewController::class, 'storeAspek3'])->name('review.aspek3');

        Route::post('review/{penugasan}/selesai', [BeritaAcaraController::class, 'tandaiSelesai'])->name('review.selesai');
        Route::post('review/{penugasan}/generate-ba', [BeritaAcaraController::class, 'generate'])->name('review.generate');
        Route::get('review/{penugasan}/ba/download', [BeritaAcaraController::class, 'downloadWord'])->name('review.ba.download');
        Route::post('review/{penugasan}/ba/upload', [BeritaAcaraController::class, 'uploadPdf'])->name('review.ba.upload');
    });

Route::middleware(['auth', 'role:satker'])
    ->prefix('satker')
    ->name('satker.')
    ->group(function () {
        Route::get('sasaran', [SasaranKegiatanController::class, 'index'])->name('sasaran.index');
        Route::post('sasaran', [SasaranKegiatanController::class, 'store'])->name('sasaran.store');
        Route::put('sasaran/{sasaran}', [SasaranKegiatanController::class, 'update'])->name('sasaran.update');
        Route::delete('sasaran/{sasaran}', [SasaranKegiatanController::class, 'destroy'])->name('sasaran.destroy');

        Route::post('sasaran/{sasaran}/indikator', [IndikatorKinerjaController::class, 'store'])->name('indikator.store');
        Route::put('indikator/{indikator}', [IndikatorKinerjaController::class, 'update'])->name('indikator.update');
        Route::delete('indikator/{indikator}', [IndikatorKinerjaController::class, 'destroy'])->name('indikator.destroy');

        Route::get('lkj', [LkjSubmissionController::class, 'index'])->name('lkj.index');
        Route::post('lkj', [LkjSubmissionController::class, 'store'])->name('lkj.store');
        Route::get('lkj/dokumen/{dokumen}/download', [LkjSubmissionController::class, 'download'])->name('lkj.download');

        Route::get('revisi', [RevisiController::class, 'index'])->name('revisi.index');
        Route::post('revisi', [RevisiController::class, 'store'])->name('revisi.store');
    });

require __DIR__.'/auth.php';
