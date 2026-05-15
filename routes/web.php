<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisDataController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Controllers\PortalDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistikController;
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
Route::get('/view-file/{id}', [FileController::class, 'viewFile'])->name('view.file');
Route::get('/download-template-excel', [FileController::class, 'downloadTemplate'])->name('download.template');
Route::get('/api-portal-data/{slug}', [PortalDataController::class, 'api_portal_data'])->name('api_portal_data');
Route::post('/kritik-saran', [KritikSaranController::class, 'store'])
    ->middleware('throttle:1,1')
    ->name('kritik.store');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::get('/users/api-get-all', [UserController::class, 'apiIndex'])->name('users.api.getAll');
    Route::post('/users/simpan', [UserController::class, 'store'])->name('users.save');
    Route::patch('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/get-form-role/{id}', [UserController::class, 'apiFormRole'])->name('users.formRole');
    Route::post('/users/simpan-role', [UserController::class, 'storeRole'])->name('users.storeRole');

    //Kritik dan Saran
    Route::get('/kritik-saran', [KritikSaranController::class, 'index'])->name('admin.kritik.index');
    Route::delete('/kritik-saran/{id}', [KritikSaranController::class, 'destroy'])->name('admin.kritik.destroy');

    //Statistik
    Route::get('/statistik/kategori-data', [StatistikController::class, 'KategoriData'])->name('admin.statistik.kategori-data');
    Route::get('/statistik/kategori-data/search-referensi', [StatistikController::class, 'searchReferensi'])->name('admin.statistik.kategori-data.searchReferensi');
    Route::post('/statistik/kategori-data/simpan', [StatistikController::class, 'storeKategoriData'])->name('admin.statistik.kategori-data.simpan');
    Route::patch('/statistik/kategori-data/update/{kategoriData}', [StatistikController::class, 'updateKategoriData'])->name('admin.statistik.kategori-data.update');
    Route::delete('/statistik/kategori-data/{kategoriData}', [StatistikController::class, 'destroyKategoriData'])->name('admin.statistik.kategori-data.destroy');

    Route::get('/statistik/{kategori}/group-kategori', [StatistikController::class, 'GroupKategori'])->name('admin.statistik.group-kategori');
    Route::post('/statistik/{kategori}/group-kategori/simpan', [StatistikController::class, 'storeGroupKategori'])->name('admin.statistik.group-kategori.simpan');
    Route::patch('/statistik/group-kategori/update/{groupKategori}', [StatistikController::class, 'updateGroupKategori'])->name('admin.statistik.group-kategori.update');
    Route::delete('/statistik/group-kategori/{groupKategori}', [StatistikController::class, 'destroyGroupKategori'])->name('admin.statistik.group-kategori.destroy');

    Route::get('/statistik/{groupKategori}/group-kategori-item', [StatistikController::class, 'GroupKategoriItem'])->name('admin.statistik.group-kategori-items');
    Route::post('/statistik/group-kategori-item/simpan', [StatistikController::class, 'storeGroupKategoriItem'])->name('admin.statistik.group-kategori-items.simpan');
    Route::delete('/statistik/group-kategori-item/{groupKategoriItem}', [StatistikController::class, 'destroyGroupKategoriItem'])->name('admin.statistik.group-kategori-items.destroy');

    Route::get('/statistik/isi-statistik/get-kategori-data', [StatistikController::class, 'getKategoriData'])->name('admin.statistik.isi-statistik.getKategoriData');
    Route::get('/statistik/isi-statistik/get-group-kategori-group/{kategoriDataId}', [StatistikController::class, 'getGroupKategori'])->name('admin.statistik.isi-statistik.getGroupKategori');
    Route::get('/statistik/isi-statistik/get-group-kategori-item/{groupKategoriId}', [StatistikController::class, 'getGroupKategoriItem'])->name('admin.statistik.isi-statistik.getGroupKategoriItem');

    Route::get('/statistik/isi-statistik', [StatistikController::class, 'IsiStatistik'])->name('admin.statistik.isi-statistik');
    Route::post('/statistik/isi-statistik/simpan', [StatistikController::class, 'storeIsiStatistik'])->name('admin.statistik.isi-statistik.store');
    Route::patch('/statistik/isi-statistik/update/{isiStatistik}', [StatistikController::class, 'updateIsiStatistik'])->name('admin.statistik.isi-statistik.update');
    Route::delete('/statistik/isi-statistik/{isiStatistik}', [StatistikController::class, 'destroyIsiStatistik'])->name('admin.statistik.isi-statistik.destroy');
});

require __DIR__ . '/auth.php';
