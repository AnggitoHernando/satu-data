<?php

use App\Http\Controllers\JenisDataController;
use App\Http\Controllers\PortalDataController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});
Route::get('/jenis_data_all', [JenisDataController::class, 'apiIndex']);
Route::get('/portal-data', [PortalDataController::class, 'apiIndex']);
Route::get('/api-detail-data', [PortalDataController::class, 'apiDetail']);
