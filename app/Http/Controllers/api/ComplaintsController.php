<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:missed_collection,illegal_dumping',
            'schedule_id' => [
                'nullable',
                'required_if:report_type,missed_collection',
                'exists:collection_schedules,id'
            ],
            'location' => 'required_if:report_type,illegal_dumping|string',
            'description' => 'required|string',
            'photo_urls.*' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {

            $photoUrls = [];
            if ($request->photo_urls) {
                foreach ($request->photo_urls as $base64Image) {
                    
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type))
                    {
                        $data = substr($base64Image, strpos($base64Image, ',') + 1);
                        
                        $data = base64_decode($data);
                        
                        $extension = $type[1];
                        
                        $filename = uniqid() . '.' . $extension;
                        
                        Storage::disk('public')->put('complaints/photos/' . $filename, $data);
                        
                        $photoUrls[] = 'complaints/photos/' . $filename;
                    }
                }
            }

            if($request->report_type === 'missed_collection')
            {
                $report = Report::create([
                    'reference_number' => 'RPT-' . strtoupper(uniqid()),
                    'resident_id' => Auth::user()->id,
                    'schedule_id' => $request->schedule_id,
                    'report_type' => $request->report_type,
                    'barangay' => Auth::user()->barangay,
                    'description' => $request->description,
                    'status' => 'pending',
                    'photo_url' => json_encode($photoUrls),
                ]);
            }else{
                $report = Report::create([
                    'reference_number' => 'RPT-' . strtoupper(uniqid()),
                    'resident_id' => Auth::user()->id,
                    'report_type' => $request->report_type,
                    'location' => $request->location,
                    'barangay' => Auth::user()->barangay,
                    'description' => $request->description,
                    'status' => 'pending',
                    'photo_url' => json_encode($photoUrls),
                ]);
            }
            
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
            $report = Report::with('resident')->with('schedule')->where('reference_number', $reference_number)->firstOrFail();

            return response()->json([
                'success' => true,
                'report' => $report
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Complaint not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function list()
    {
        try {
            $resident = Auth::user(); // Ensure the resident is authenticated

            $reports = Report::where('resident_id', $resident->id)
                    ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

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
