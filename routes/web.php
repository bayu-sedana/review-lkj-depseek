<?php

use App\Http\Controllers\PenugasanMonevController;
use App\Http\Controllers\PeriodeReviewController;
use App\Http\Controllers\ProfileController;
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

require __DIR__.'/auth.php';
