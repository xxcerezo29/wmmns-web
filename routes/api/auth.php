<?php

use App\Http\Controllers\api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\api\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\api\Auth\RegisteredUserController;
use App\Http\Controllers\api\Auth\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('user', function (Request $request){
        return response()->json($request->user());
    });

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1');
    Route::get('verify-email', VerifyEmailController::class);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
});


