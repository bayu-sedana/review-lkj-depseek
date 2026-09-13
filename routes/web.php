<?php

use App\Http\Controllers\PenugasanMonevController;
use App\Http\Controllers\PeriodeReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Satker\IndikatorKinerjaController;
use App\Http\Controllers\Satker\LkjSubmissionController;
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
        Route::resource('satkers', SatkerController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');
        Route::resource('periodes', PeriodeReviewController::class)
            ->parameters(['periodes' => 'periode'])
            ->except('show');

        Route::get('penugasan', [PenugasanMonevController::class, 'index'])->name('penugasan.index');
        Route::post('penugasan', [PenugasanMonevController::class, 'store'])->name('penugasan.store');
        Route::delete('penugasan/{penugasan}', [PenugasanMonevController::class, 'destroy'])->name('penugasan.destroy');
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
    });

require __DIR__.'/auth.php';
