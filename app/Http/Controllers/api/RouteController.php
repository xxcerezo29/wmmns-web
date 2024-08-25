<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function all()
    {
        $routes = Route::all();

        return response()->json([
            'routes' => $routes
        ]);
    }

    public function getByBarangay($barangay){
        $routes = Route::where('barangay', $barangay)->get();

        return response()->json([
            'routes' => $routes
        ]);
    }

    public function getById($id){
        $route = Route::findOrFail($id);

        return response()->json([
            'route' => $route
        ]);
    }
}
