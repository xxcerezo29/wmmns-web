<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GarbageCollectionScheduleController extends Controller
{
    public function list(){
        return Inertia::render('Schedule/List');
    }

    public function create(){
        if(Auth::user()->hasRole('admin')){
            $trucks = Truck::all();
        }else{
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
        }
        return Inertia::render('Schedule/Create', [
            'trucks' => $trucks
        ]);
    }
}
