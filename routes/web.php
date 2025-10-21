<?php

use App\Http\Controllers\JenisDataController;
use App\Http\Controllers\ProfileController;
use App\Models\JenisData;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home/Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('Beranda');

Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/jenis-data', [JenisDataController::class, 'index'])->name('jenis_data.show');
    Route::post('/jenis-data/simpan', [JenisDataController::class, 'store'])->name('jenis_data.save');
    Route::delete('/jenis-data/{jenisData}', [JenisDataController::class, 'destroy'])->name('jenis_data.destroy');
    Route::patch('/jenis-data/update/{jenisData}', [JenisDataController::class, 'update'])->name('jenis_data.update');
    Route::patch('/jenis-data/updateStatus/{jenisData}', [JenisDataController::class, 'updateStatus'])->name('jenis_data.update_status');
});

require __DIR__ . '/auth.php';
