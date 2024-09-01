<?php

use App\Http\Controllers\api\DriverProfileController;
use App\Http\Controllers\api\ResidentProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('profile')->group(function(){
        Route::post('/driver', [DriverProfileController::class, 'update']);
        Route::post('/resident', [ResidentProfileController::class, 'update']);
    });
});