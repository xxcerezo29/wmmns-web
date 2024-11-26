<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CollectionSchedule;
use App\Models\Driver;
use App\Models\Resident;
use App\Models\Truck;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GarbageCollectionSchedule extends Controller
{
    public function all()
    {
        $user = Auth::user();

        if ($user instanceof Driver) {
            $truck = $user->AssignedTruck;
            $schedules = CollectionSchedule::query()
                ->with(['truck', 'route'])
                ->where('truck_id', $user->truck_id)
                ->orderBy('schedule')
                ->orderBy('time')
                ->get()
                ->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'start' => Carbon::parse($schedule->schedule . ' ' . $schedule->time)->toDateTimeString(),
                        'end' => Carbon::parse($schedule->schedule . ' ' . $schedule->time)->addHours(4)->toDateTimeString(),
                        'title' => "Collection for " . $schedule->barangay,
                        'icon' => 'truck',
                        'content' => "Route: {$schedule->route->name}, Truck: {$schedule->truck->name}",
                        'contentFull' => "Barangay: {$schedule->barangay}<br>Route: {$schedule->route->name}<br>Truck: {$schedule->truck->plate_number}<br>Time: {$schedule->time}",
                        'class' => 'collection-schedule',
                    ];
                });
        } elseif ($user instanceof Resident) {
            $schedules = CollectionSchedule::query()
                ->with(['truck', 'route'])
                ->where('barangay', $user->barangay)
                ->orderBy('schedule')
                ->orderBy('time')
                ->get()
                ->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'start' => Carbon::parse($schedule->schedule . ' ' . $schedule->time)->toDateTimeString(),
                        'end' => Carbon::parse($schedule->schedule . ' ' . $schedule->time)->addHours(4)->toDateTimeString(),
                        'title' => "Collection for " . $schedule->barangay,
                        'icon' => 'truck',
                        'content' => "Route: {$schedule->route->name}, Truck: {$schedule->truck->name}",
                        'contentFull' => "Barangay: {$schedule->barangay}<br>Route: {$schedule->route->name}<br>Truck: {$schedule->truck->plate_number}<br>Time: {$schedule->time}",
                        'class' => 'collection-schedule',
                    ];
                });
        } else {
            return response()->json([
                'error' => 'Unauthorized user type'
            ], 403);
        }

        return response()->json([
            'schedules' => $schedules
        ]);
    }

    public function getById($id)
    {
        $schedule = CollectionSchedule::with('truck')
            ->with('route')
            ->findOrFail($id);

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
    public function getTrucksForToday()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();

        $schedules = CollectionSchedule::where('schedule', $today)
            ->where('barangay', $user->barangay)
            ->with('truck')
            ->get();

        $trucks = $schedules->map(function ($schedule) {
            return $schedule->truck;
        })->unique('id')->values();

        return response()->json([
            'trucks' => $trucks
        ]);
    }
    public function getByDay($day)
    {
        $user = Auth::user();

        if ($user instanceof Driver) {
            $schedules = CollectionSchedule::where('barangay', $user->barangay)
                ->where('schedule', $day)
                ->where('truck_id', $user->truck_id)
                ->with('truck')
                ->with('route')
                ->orderBy('time', 'asc')
                ->get();
        } else if ($user instanceof Resident) {
            $schedules = CollectionSchedule::where('barangay', $user->barangay)
                ->where('schedule', $day)
                ->with('truck')
                ->with('route')
                ->orderBy('time', 'asc')
                ->get()
                ->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'barangay' => $schedule->barangay,
                        'schedule' => $schedule->schedule,
                        'truck_id' => $schedule->truck_id,
                        'route_id' => $schedule->route_id,
                        'time' => $schedule->time,
                        'truck' => $schedule->truck,
                        'plate_number' => $schedule->truck->plate_number ?? 'N/A', // Add truck plate number if exists
                        'route_name' => $schedule->route->name ?? ''
                    ];
                });
        } else {
            return response()->json([
                'error' => 'Unauthorized user type'
            ], 403);
        }



        return response()->json([
            'schedules' => $schedules
        ]);
    }

    public function list()
    {
        try {
            $resident = Auth::user();

            $schedules = CollectionSchedule::where('barangay', $resident->barangay)
                ->with('truck')
                ->with('route')->get();

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reports.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
