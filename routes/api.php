<?php

use App\Http\Controllers\JenisDataController;
use App\Http\Controllers\PortalDataController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});
Route::get('/portal-data', [PortalDataController::class, 'apiIndex']);
Route::get('/api-detail-data', [PortalDataController::class, 'apiDetail']);
Route::get('/users-all', [UserController::class, 'apiIndex']);
