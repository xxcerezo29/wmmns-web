<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CollectionSchedule;
use App\Models\Driver;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GarbageCollectionSchedule extends Controller
{
    public function all()
    {
        $schedules = CollectionSchedule::all();

        return response()->json([
            'schedules' => $schedules
        ]);
    }

    public function getById($id){
        $schedule = CollectionSchedule::findOrFail($id);

        return response()->json([
            'schedules' => $schedule
        ]);
    }

    public function getByUser()
    {
        $driver = Auth::user();
        $schedules = $driver->AssignedTruck->schedule;

        return response()->json([
            'schedules' => $schedules
        ]);
    }

    public function getByTruck($id)
    {
        $truck = Truck::findOrFail($id);
        
        $schedules = $truck->schedule;

        return response()->json([
            'schedules' => $schedules
        ]);
    }
    public function getByDay($day)
    {
        $schedules = CollectionSchedule::where('day', $day)->get();

        return response()->json([
            'schedules' => $schedules
        ]);
    }
}
