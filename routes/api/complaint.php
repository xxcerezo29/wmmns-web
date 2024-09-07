<?php

use App\Http\Controllers\api\ComplaintsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('complaints')->group(function () {
        Route::post('/create' , [ComplaintsController::class, 'store']);
        Route::get('/show/{reference_number}', [ComplaintsController::class, 'show']);
    });
});