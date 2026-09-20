<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisDataController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Controllers\PortalDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\PpidInformasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuInformasiController;
use App\Http\Controllers\HalamanStatisController;
use App\Models\JenisData;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'ppid'])->name('Beranda');

Route::get('/mandat/portal-data', [PortalDataController::class, 'index'])->name('PortalData');
Route::get('/mandat/portal-data/search', [PortalDataController::class, 'search'])->name('PortalData.search');
Route::get('/mandat/portal-data/{slug}', [PortalDataController::class, 'detail'])->name('PortalData.detail');
Route::get('/mandat/portal-data/statistik/{slug}', [PortalDataController::class, 'detailStatistik'])->name('PortalData.statistik.detail');
Route::get('/mandat/download/{id}', [FileController::class, 'download'])->name('download.file');
Route::get('/mandat/view-file/{id}', [FileController::class, 'viewFile'])->name('view.file');
Route::get('/mandat/download-template-excel', [FileController::class, 'downloadTemplate'])->name('download.template');
Route::get('/mandat/api-portal-data/{slug}', [PortalDataController::class, 'api_portal_data'])->name('api_portal_data');
Route::post('/kritik-saran', [KritikSaranController::class, 'store'])
    ->middleware('throttle:1,1')
    ->name('kritik.store');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
//PPID HALAMAN UTAMA
Route::get('/ppid', [HomeController::class, 'ppid'])->name('home.ppid');
Route::get('/informasi-berkala', [HomeController::class, 'informasi_berkala'])->name('home.ppid.informasi_berkala');
Route::get('/informasi-serta-merta', [HomeController::class, 'informasiSertaMerta'])->name('home.ppid.informasiSertaMerta');
Route::get('/informasi-setiap-saat', [HomeController::class, 'informasiSetiapSaat'])->name('home.ppid.informasiSetiapSaat');
Route::get('/informasi-dikecualikan', [HomeController::class, 'informasiDikecualikan'])->name('home.ppid.informasiDikecualikan');
Route::get('/permohonan-informasi', [HomeController::class, 'permohonan_informasi'])->name('home.ppid.permohonan_informasi');
Route::get('/lacak-permohonan-informasi', [HomeController::class, 'lacak_permohonan_informasi'])->name('home.ppid.lacak_permohonan_informasi');
Route::get('/permohonan-keberatan', [HomeController::class, 'lacak_permohonan_keberatan'])->name('home.ppid.permohonan_keberatan');
Route::get('/{menu}/{slug}', [HomeController::class, 'detailInformasi'])->name('home.ppid.detailInformasi');

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
    Route::post('/statistik/group-kategori/AutoAddGroupKecamatan/simpan/{kategori}', [StatistikController::class, 'storeAddGroupKecamatan'])->name('admin.statistik.group-kategori.autoAddGroupKecamatan.simpan');

    Route::get('/statistik/{groupKategori}/group-kategori-item', [StatistikController::class, 'GroupKategoriItem'])->name('admin.statistik.group-kategori-items');
    Route::post('/statistik/group-kategori-item/simpan', [StatistikController::class, 'storeGroupKategoriItem'])->name('admin.statistik.group-kategori-items.simpan');
    Route::delete('/statistik/group-kategori-item/{groupKategoriItem}', [StatistikController::class, 'destroyGroupKategoriItem'])->name('admin.statistik.group-kategori-items.destroy');
    Route::post('/statistik/group-kategori-item/kecamatan/{groupKategori}', [StatistikController::class, 'storeAutoKecamatan'])->name('admin.statistik.group-kategori.kecamatan.auto');
    Route::post('/statistik/group-kategori-item/simpan-bulk', [StatistikController::class, 'storeBulkGroupKategoriItems'])->name('admin.statistik.group-kategori-items.bulk-store');

    Route::get('/statistik/isi-statistik/get-kategori-data', [StatistikController::class, 'getKategoriData'])->name('admin.statistik.isi-statistik.getKategoriData');
    Route::get('/statistik/isi-statistik/get-group-kategori-group/{kategoriDataId}', [StatistikController::class, 'getGroupKategori'])->name('admin.statistik.isi-statistik.getGroupKategori');
    Route::get('/statistik/isi-statistik/get-group-kategori-item/{groupKategoriId}', [StatistikController::class, 'getGroupKategoriItem'])->name('admin.statistik.isi-statistik.getGroupKategoriItem');
    Route::get('/statistik/isi-statistik/get-group-kategori-item/{groupKategoriId}/{tahun}', [StatistikController::class, 'getGroupKategoriItemBatch'])->name('admin.statistik.isi-statistik.getGroupKategoriItemBatch');

    Route::get('/statistik/isi-statistik', [StatistikController::class, 'IsiStatistik'])->name('admin.statistik.isi-statistik');
    Route::post('/statistik/isi-statistik/simpan', [StatistikController::class, 'storeIsiStatistik'])->name('admin.statistik.isi-statistik.store');
    Route::patch('/statistik/isi-statistik/update/{isiStatistik}', [StatistikController::class, 'updateIsiStatistik'])->name('admin.statistik.isi-statistik.update');
    Route::delete('/statistik/isi-statistik/{isiStatistik}', [StatistikController::class, 'destroyIsiStatistik'])->name('admin.statistik.isi-statistik.destroy');

    Route::get('/statistik/excel/download-template', [StatistikController::class, 'downloadTemplate'])
        ->name('admin.statistik.excel.download-template');
    Route::post('/statistik/excel/upload', [StatistikController::class, 'uploadIsiStatistik'])
        ->name('admin.statistik.excel.upload');

    //PPID ADMIN TAMBAH MENU INFORMASI
    Route::get('/ppid-informasi/menu-informasi', [MenuInformasiController::class, 'index'])->name('admin.ppid.menu-informasi');
    Route::get('/ppid-informasi/menu-informasi/tambah', [MenuInformasiController::class, 'create'])->name('admin.ppid.menu-informasi.create');
    Route::get('/ppid-informasi/menu-informasi/create-sub-menu/{id}', [MenuInformasiController::class, 'createSubMenu'])->name('admin.ppid.menu-informasi.create-sub-menu');
    Route::get('/ppid-informasi/menu-informasi/edit/{menuInformasi}', [MenuInformasiController::class, 'edit'])->name('admin.ppid.menu-informasi.edit');
    Route::get('/ppid-informasi/menu-informasi/get-menu-informasi', [MenuInformasiController::class, 'getMenuInformasi'])->name('admin.ppid.get-menu-informasi');
    Route::put('/ppid-informasi/menu-informasi/update/{menuInformasi}', [MenuInformasiController::class, 'update'])->name('admin.ppid.menu-informasi.update');
    Route::post('/ppid-informasi/menu-informasi/simpan', [MenuInformasiController::class, 'store'])->name('admin.ppid.menu-informasi.simpan');
    Route::delete('/ppid-informasi/menu-informasi/{menuInformasi}', [MenuInformasiController::class, 'destroy'])->name('admin.ppid.menu-informasi.destroy');

    //PPID ADMIN TAMBAH INFORMASI
    Route::get('/ppid-informasi/tambah-informasi', [PpidInformasiController::class, 'tambahInformasi'])->name('admin.ppid.tambah-informasi');
    Route::get('/ppid-informasi/tambah-informasi/tambah-data', [PpidInformasiController::class, 'tambahDataInformasi'])->name('admin.ppid.tambah-informasi.tambah-data');
    Route::get('/ppid-informasi/get-jenis-data', [PpidInformasiController::class, 'getJenisData'])->name('admin.ppid.get-jenis-data');
    Route::get('/ppid-informasi/get-menu', [PpidInformasiController::class, 'getMenu'])->name('admin.ppid.get-menu');
    Route::post('/ppid-informasi/tambah-informasi/simpan', [PpidInformasiController::class, 'storeInformasi'])->name('admin.ppid.tambah-informasi.simpan');
    Route::get('/ppid-informasi/tambah-informasi/edit/{ppidInformasi}', [PpidInformasiController::class, 'editInformasi'])->name('admin.ppid.tambah-informasi.edit');
    Route::delete('/ppid-informasi/tambah-informasi/delete/{ppidInformasi}', [PpidInformasiController::class, 'destroyInformasi'])->name('admin.ppid.tambah-informasi.delete');
    Route::match(['put', 'patch'], 'ppid-informasi/tambah-informasi/update/{ppidInformasi}', [PpidInformasiController::class, 'updateInformasi'])->name('admin.ppid.tambah-informasi.update');

    //PPID ADMIN HALAMAN STATIS
    Route::get('/ppid-informasi/halaman-statis', [HalamanStatisController::class, 'index'])->name('admin.ppid.halaman-statis');
    Route::get('/ppid-informasi/halaman-statis/tambah-data/{menu}', [HalamanStatisController::class, 'create'])->name('admin.ppid.halaman-statis.create');
    Route::get('/ppid-informasi/halaman-statis/edit/{menu}', [HalamanStatisController::class, 'edit'])->name('admin.ppid.halaman-statis.edit');
    Route::get('/ppid-informasi/get-menu-statis', [HalamanStatisController::class, 'getMenuStatis'])->name('admin.ppid.get-menu-statis');
    Route::post('/ppid-informasi/halaman-statis/simpan', [HalamanStatisController::class, 'store'])->name('admin.ppid.halaman-statis.simpan');
    Route::delete('/ppid-informasi/halaman-statis/delete/{halamanStatis}', [HalamanStatisController::class, 'destroy'])->name('admin.ppid.halaman-statis.destroy');
    Route::match(['put', 'patch'], '/ppid-informasi/halaman-statis/update/{halamanStatis}', [HalamanStatisController::class, 'update'])->name('admin.ppid.halaman-statis.update');
});

require __DIR__ . '/auth.php';
