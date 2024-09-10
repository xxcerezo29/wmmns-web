<?php

use App\Http\Controllers\api\GarbageCollectionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('schedule')->group(function(){
        Route::get('/', [GarbageCollectionSchedule::class, 'all']);
        Route::get('get-by-id/{id}', [GarbageCollectionSchedule::class, 'getById']);
        Route::get('get-by-user', [GarbageCollectionSchedule::class, 'getByUser']);
        Route::get('get-by-truck/{id}', [GarbageCollectionSchedule::class,'getByTruck']);
        Route::get('get-by-day/{day}', [GarbageCollectionSchedule::class,'getByDay']);
        Route::get('get-trucks-today', [GarbageCollectionSchedule::class, 'getTrucksForToday']);
        Route::get('/list', [GarbageCollectionSchedule::class, 'list']);
    });
});