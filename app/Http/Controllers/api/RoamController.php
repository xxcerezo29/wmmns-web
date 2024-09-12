<?php

namespace App\Http\Controllers\api;

use App\Events\TrackGarbageTruck;
use App\Events\TrackGarbageTruckWeb;
use App\Http\Controllers\Controller;
use App\Models\Roam;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoamController extends Controller
{
    public function store(Request $request){
        $validatedData  = $request->validate([
            'driver_id' => 'required|integer',
            'schedule_id' => 'required|integer',
            'started' => 'required|date_format:Y-m-d H:i:s',
        ]);

        DB::beginTransaction();
        try{
            $roam = Roam::create($validatedData);

            
            DB::commit();
            return response()->json([
                'roam' => $roam,
                'message' => 'Roam started successfully.'
            ], 201);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to start roam. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id){
        
        $validatedData = $request->validate([
            'ended' => 'required|date_format:Y-m-d H:i:s',
        ]);
        DB::beginTransaction();
        try{

            $roam = Roam::findOrFail($id);

            $roam->update($validatedData);

            DB::commit();
            return response()->json([
                'roam' => $roam,
                'message' => 'Roam ended successfully.'
            ], 200);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to end roam. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel ($id){
        DB::beginTransaction();
        try{
            $roam = Roam::findOrFail($id);

            $roam->delete();
            DB::commit();

            return response()->json([
                'roam' => $roam,
                'message' => 'Roaming Canceled.'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to cancel roam. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 

    public function sendLocation (Request $request){

        $user = Auth::user();
        $truck = $user->AssignedTruck;

        $location = $request->input('location');
        $barangay = $user->barangay;

        event(new TrackGarbageTruck($location, $barangay, $user,$truck));
        event(new TrackGarbageTruckWeb($location, $barangay, $user,$truck));

        return response()->json(['status' => 'Location Sent']);
    }
}
