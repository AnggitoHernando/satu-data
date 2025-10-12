<?php

use App\Http\Controllers\JenisDataController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});
Route::get('/jenis_data_all', [JenisDataController::class, 'apiIndex']);
