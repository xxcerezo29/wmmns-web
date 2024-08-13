<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResidentsController extends Controller
{
    public function list()
    {
        $residents = Resident::paginate(10);
        return Inertia::render('Residents/List', [
            'residents' => $residents
        ]);
    }
}
