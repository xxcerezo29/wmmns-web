<?php

use App\Http\Controllers\api\RoamController;
use App\Http\Controllers\api\RouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('roams')->group(function () {
        Route::post('/start' , [RoamController::class, 'store']);
        Route::post('/end/{id}', [RoamController::class, 'update']);
        Route::post('/cancel/{id}', [RoamController::class, 'cancel']);
    });
});