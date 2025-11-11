<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisDataController;
use App\Http\Controllers\PortalDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\JenisData;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('Beranda');

Route::get('/portal-data', [PortalDataController::class, 'index'])->name('PortalData');
Route::get('/portal-data/search', [PortalDataController::class, 'search'])->name('PortalData.search');
Route::get('/portal-data/{slug}', [PortalDataController::class, 'detail'])->name('PortalData.detail');
Route::get('/download/{id}', [FileController::class, 'download'])->name('download.file');
Route::get('/api-portal-data/{slug}', [PortalDataController::class, 'api_portal_data'])->name('api_portal_data');


Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Jenis Data
    Route::get('/jenis-data', [JenisDataController::class, 'index'])->name('jenis_data.show');
    Route::get('/jenis_data_all', [JenisDataController::class, 'apiIndex'])->name('jenis_data.api-show');
    Route::post('/jenis-data/simpan', [JenisDataController::class, 'store'])->name('jenis_data.save');
    Route::delete('/jenis-data/{jenisData}', [JenisDataController::class, 'destroy'])->name('jenis_data.destroy');
    Route::patch('/jenis-data/update/{jenisData}', [JenisDataController::class, 'update'])->name('jenis_data.update');
    Route::patch('/jenis-data/updateStatus/{jenisData}', [JenisDataController::class, 'updateStatus'])->name('jenis_data.update_status');
    Route::get('/jenis-data/{id}/status', [JenisDataController::class, 'status'])->name('jenis-data.status_upload');
    Route::post('/jenis-data/{id}/retryUpload', [JenisDataController::class, 'retryUpload'])->name('jenis-data.retryUpload');
    //User
    Route::get('/users', [UserController::class, 'index'])->name('users.show');
    Route::post('/users/simpan', [UserController::class, 'store'])->name('users.save');
    Route::patch('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/get-form-role/{id}', [UserController::class, 'apiFormRole'])->name('users.formRole');
    Route::post('/users/simpan-role', [UserController::class, 'storeRole'])->name('users.storeRole');
});

require __DIR__ . '/auth.php';
