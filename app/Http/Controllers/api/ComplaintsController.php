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
            'photo_url' => 'nullable|url',
            'resolved_at' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            $report = Report::create([
                'reference_number' => 'RPT-' . strtoupper(uniqid()),
                'resident_id' => Auth::user()->id,
                'schedule_id' => $request->schedule_id,
                'report_type' => $request->report_type,
                'location' => $request->location,
                'barangay' => Auth::user()->barangay,
                'description' => $request->description,
                'status' => 'pending',
                'photo_url' => $request->photo_url,
                'resolved_at' => $request->resolved_at
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
}
