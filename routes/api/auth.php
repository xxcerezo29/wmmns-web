<?php

use App\Http\Controllers\api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\api\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\api\Auth\NewPasswordController;
use App\Http\Controllers\api\Auth\PasswordController;
use App\Http\Controllers\api\Auth\PasswordResetLinkController;
use App\Http\Controllers\api\Auth\RegisteredUserController;
use App\Http\Controllers\api\Auth\VerifyEmailController;
use App\Models\Driver;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function PHPSTORM_META\type;

Route::middleware('guest')->group(function () {
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);
    Route::post('reset-password', [NewPasswordController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        $user = $request->user();
        if ($user instanceof Driver) {
            return response()->json([
                'type' => 'driver',
                'user' => $user
            ]);
        } elseif ($user instanceof Resident) {
            return response()->json([
                'type' => 'resident',
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'type' => 'unknown',
                'user' => $request->user()
            ]);
        }
    });

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1');
    Route::get('verify-email', VerifyEmailController::class);
    Route::post('password' , [PasswordController::class, 'update']);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
});
