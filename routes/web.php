<?php

use App\Http\Controllers\ComplaintsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\GarbageCollectionScheduleController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RoutePlanController;
use App\Http\Controllers\SpatialMapController;
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


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::prefix('trucks')->group(function(){
        Route::get('/all', [TrucksController::class, 'list'])->name('trucks.list');
        Route::get('/truck-create', [TrucksController::class, 'create'])->name('trucks.create');
        Route::post('/truck-create', [TrucksController::class, 'store'])->name('trucks.store');
        Route::get('/truck-edit/{id}', [TrucksController::class, 'edit'])->name('trucks.edit');
        Route::delete('/truck-delete/{id}', [TrucksController::class, 'destroy'])->name('trucks.delete');
        Route::get('/truck-show/{id}', [TrucksController::class, 'show'])->name('trucks.show');
        Route::get('/truck-list', [TrucksController::class, 'downloadPDF'])->name('trucks.pdf');
    });

    Route::prefix('routes')->group(function() {
        Route::get('/', [RoutePlanController::class, 'list'])->name('routes.list');
        Route::get('/route-create', [RoutePlanController::class, 'create'])->name('routes.create');
        Route::post('/route-create', [RoutePlanController::class, 'store'])->name('routes.store');
        Route::get('/route-edit/{id}', [RoutePlanController::class, 'edit'])->name('routes.edit');
        Route::post('/route-edit/{id}', [RoutePlanController::class, 'update'])->name('routes.update');
        Route::delete('/route-delete/{id}', [RoutePlanController::class, 'destroy'])->name('routes.destroy');
        Route::get('/route-show/{id}', [RoutePlanController::class, 'show'])->name('routes.show');
    });

    Route::prefix('schedule')->group(function(){
        Route::get('/', [GarbageCollectionScheduleController::class,'list'])->name('schedule.calendar');
        Route::get('/schedule-create', [GarbageCollectionScheduleController::class,'create'])->name('schedule.create');
        Route::get('/schedule-view/{id}', [GarbageCollectionScheduleController::class,'show'])->name('schedule.show');
        Route::get('/schedule-edit/{id}', [GarbageCollectionScheduleController::class,'edit'])->name('schedule.edit');
        Route::post('/schedule-edit/{id}', [GarbageCollectionScheduleController::class,'update'])->name('schedule.update');
        Route::post('/schedule-create', [GarbageCollectionScheduleController::class,'store'])->name('schedule.store');
        Route::get('/schedule-list', [GarbageCollectionScheduleController::class, 'downloadPDF'])->name('schedule.pdf');
    });

    Route::prefix('users')->group(function () {
        Route::get('/all', [UsersController::class, 'list'])->name('users.all.list');
        Route::get('/user-update/{id}', [UsersController::class, 'edit'])->name('users.all.edit');
        Route::post('/user-update/{id}', [UsersController::class, 'update'])->name('users.all.update');
        Route::post('/user-create', [UsersController::class, 'store'])->name('users.all.store');
        Route::get('/user-create', [UsersController::class, 'create'])->name('users.all.create');
        Route::delete('/user-delete/{id}', [UsersController::class, 'destroy'])->name('users.all.delete');
        Route::get('/user-show/{id}', [UsersController::class, 'show'])->name('users.show');
        Route::get('/users-list', [UsersController::class, 'downloadPDF'])->name('users.pdf');

        Route::prefix('residents')->group(function(){
            Route::get('/', [ResidentsController::class, 'list'])->name('users.residents.list');
            Route::get('/resident-show/{id}', [ResidentsController::class, 'show'])->name('users.residents.show');
            Route::get('/drivers-list', [ResidentsController::class, 'downloadPDF'])->name('users.residents.pdf');
        });

        Route::prefix('drivers')->group(function(){
            Route::get('/', [DriversController::class, 'list'])->name('users.drivers.list');
            Route::get('/driver-create', [DriversController::class,'create'])->name('users.drivers.create');
            Route::post('/driver-create', [DriversController::class,'store'])->name('users.drivers.store');
            Route::get('/driver-update/{id}', [DriversController::class,'edit'])->name('users.drivers.edit');
            Route::post('/driver-update/{id}', [DriversController::class,'update'])->name('users.drivers.update');
            Route::delete('/driver-delete/{id}', [DriversController::class,'destroy'])->name('users.drivers.delete');
            Route::get('/driver-show/{id}', [DriversController::class, 'show'])->name('users.drivers.show');
            Route::get('/drivers-list', [DriversController::class, 'downloadPDF'])->name('users.drivers.pdf');
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

    Route::prefix('complaints')->group(function(){
        Route::get('/', [ComplaintsController::class, 'list'])->name('complaints.list');
        Route::get('/view/{reference_number}', [ComplaintsController::class, 'view'])->name('complaints.view');
        // Route::post('/update/{reference_number}', [ComplaintsController::class, 'update_status'])->name('complaints.update');
        Route::get('/reviewed/{id}', [ComplaintsController::class, 'reviewed'])->name('complaints.reviewed');
        Route::get('/resolved/{id}', [ComplaintsController::class, 'resolved'])->name('complaints.resolved');
        Route::get('/closed/{id}', [ComplaintsController::class, 'closed'])->name('complaints.closed');
        Route::get('/complaints-list', [ComplaintsController::class, 'downloadPDF'])->name('complaints.pdf');
    });

    Route::prefix('spatial-map')->group(function(){
        Route::get('/', [SpatialMapController::class, 'view'])->name('spatial-map.view');
        Route::post('/reports', [SpatialMapController::class, 'downloadPDF'])->name('spatial-map.pdf');
    });
    Route::prefix('map')->group(function(){
        Route::get('/', [MapController::class, 'view'])->name('roam-map.view');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
