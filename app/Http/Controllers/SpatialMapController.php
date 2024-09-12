<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\SpatialData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SpatialMapController extends Controller
{
    public function view()
    {

        $spatial_map = SpatialData::all();
        $reportCounts = Report::select('barangay', DB::raw('COUNT(*) as count'))
            ->groupBy('barangay')
            ->pluck('count', 'barangay')
            ->toArray();

        return Inertia::render('SpatialMap/View', [
            'spatial_map' => $spatial_map,
            'report_counts' => $reportCounts
        ]);
    }
}
