<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/run-migrations/{password}', function($password){
    if($password === 'wmmns2024'){
        Artisan::call('migrate', ['--force'=> true]);
        return 'Migrations Have been run';
    }

    abort(403, 'Unauthorized');
});