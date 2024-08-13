<?php

use App\Http\Controllers\DriversController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TrucksController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::prefix('trucks')->group(function(){
        Route::get('/all', [TrucksController::class, 'list'])->name('trucks.list');
        Route::get('/truck-create', [TrucksController::class, 'create'])->name('trucks.create');
        Route::post('/truck-create', [TrucksController::class, 'store'])->name('trucks.store');
        Route::get('/truck-edit/{id}', [TrucksController::class, 'edit'])->name('trucks.edit');
        Route::delete('/truck-delete/{id}', [TrucksController::class, 'destroy'])->name('trucks.delete');
    });

    Route::prefix('users')->group(function () {
        Route::get('/all', [UsersController::class, 'list'])->name('users.all.list');
        Route::get('/user-update/{id}', [UsersController::class, 'edit'])->name('users.all.edit');
        Route::post('/user-update/{id}', [UsersController::class, 'update'])->name('users.all.update');
        Route::post('/user-create', [UsersController::class, 'store'])->name('users.all.store');
        Route::get('/user-create', [UsersController::class, 'create'])->name('users.all.create');
        Route::delete('/user-delete/{id}', [UsersController::class, 'destroy'])->name('users.all.delete');

        Route::prefix('residents')->group(function(){
            Route::get('/', [ResidentsController::class, 'list'])->name('users.residents.list');
        });

        Route::prefix('drivers')->group(function(){
            Route::get('/', [DriversController::class, 'list'])->name('users.drivers.list');
            Route::get('/driver-create', [DriversController::class,'create'])->name('users.drivers.create');
            Route::post('/driver-create', [DriversController::class,'store'])->name('users.drivers.store');
            Route::get('/driver-update/{id}', [DriversController::class,'edit'])->name('users.drivers.edit');
            Route::post('/driver-update/{id}', [DriversController::class,'update'])->name('users.drivers.update');
            Route::delete('/driver-delete/{id}', [DriversController::class,'destroy'])->name('users.drivers.delete');
        });

        Route::prefix('roles')->group(function () {
            Route::get('/', [RolesController::class, 'list'])->name('users.roles.list');
            Route::get('/create', [RolesController::class, 'create'])->name('users.roles.create');
            Route::get('/edit/{id}', [RolesController::class, 'edit'])->name('users.roles.edit');
            Route::post('/create', [RolesController::class, 'store'])->name('users.roles.store');
            Route::post('/edit/{id}', [RolesController::class, 'update'])->name('users.roles.update');
            Route::delete('/delete/{id}', [RolesController::class, 'delete'])->name('users.roles.delete');
        });

        Route::prefix('permissions')->group(function(){
            Route::get('/', [PermissionsController::class, 'list'])->name('users.permissions.list');
            Route::get('/create', [PermissionsController::class, 'create'])->name('users.permissions.create');
            Route::get('/edit/{id}', [PermissionsController::class, 'edit'])->name('users.permissions.edit');
            Route::post('/create', [PermissionsController::class, 'store'])->name('users.permissions.store');
            Route::post('/edit/{id}', [PermissionsController::class, 'update'])->name('users.permissions.update');
            Route::delete('/delete/{id}', [PermissionsController::class, 'delete'])->name('users.permissions.delete');
        });
    });




    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
