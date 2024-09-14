<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\SpatialData;
use Exception;
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

    public function downloadPDF(Request $request)
    {
        try {
            $imageData = $request->input('image');

            $reportCounts = Report::select('barangay', DB::raw('COUNT(*) as count'))
                ->groupBy('barangay')
                ->pluck('count', 'barangay')
                ->toArray();

            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);

            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

            $pdf->loadView('pdf.spatialmap', [
                'map' => $imageData,
                'reports' => $reportCounts
            ]);

            return $pdf->download('spatialmap.pdf');
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
