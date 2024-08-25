<?php

use App\Http\Controllers\api\RouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('routes')->group(function () {
        Route::get('/', [RouteController::class, 'all']);
        Route::get('/{id}', [RouteController::class, 'getById']);
        Route::get('/get-by-barangay/{barangay}', [RouteController::class, 'getByBarangay']);
    });
});
