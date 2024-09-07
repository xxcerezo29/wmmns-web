<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComplaintsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:missed_collection,illegal_dumping',
            'schedule_id' => 'required_if:report_type,missed_collection|exists:collection_schedules,id',
            'location' => 'required_if:report_type,illegal_dumping|string',
            'description' => 'required|string',
            'photo_url.*' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {

            $photoUrls = [];
            if ($request->hasFile('photo_urls')) {
                foreach ($request->file('photo_urls') as $file) {
                    $path = $file->store('public/photos'); // Store the photo
                    $photoUrls[] = $path;
                }
            }

            $report = Report::create([
                'reference_number' => 'RPT-' . strtoupper(uniqid()),
                'resident_id' => Auth::user()->id,
                'schedule_id' => $request->schedule_id,
                'report_type' => $request->report_type,
                'location' => $request->location,
                'barangay' => Auth::user()->barangay,
                'description' => $request->description,
                'status' => 'pending',
                'photo_urls' => json_encode($photoUrls),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Complaint filed successfully.',
                'data' => $report
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'There was an error while filing the complaint.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($reference_number)
    {
        try {
            $report = Report::where('reference_number', $reference_number)->firstOrFail();

            return response()->json([
                'success' => true,
                'report' => $report
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Report not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function list()
    {
        try {
            $resident = Auth::user(); // Ensure the resident is authenticated

            $reports = Report::where('resident_id', $resident->id)->paginate(10);

            return response()->json([
                'success' => true,
                'reports' => $reports
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reports.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
