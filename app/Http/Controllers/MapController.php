<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MapController extends Controller
{
    public function view (){
        return Inertia::render('Maps/View');
    }
}
