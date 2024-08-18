<?php

namespace App\Http\Controllers;

use App\Models\CollectionSchedule;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Truck;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class GarbageCollectionScheduleController extends Controller
{
    public function list(){
        $user = Auth::user();
        if($user->hasRole('admin'))
            $schedule = CollectionSchedule::with('truck')->with('route')->orderBy('day')->orderBy('time')->paginate(10);
        else
        $schedule = CollectionSchedule::where('barangay', $user->barangay)->with('truck')->with('route')->orderBy('day')->orderBy('time')->paginate(10);
        
        $group = $schedule->groupBy('day');

        return Inertia::render('Schedule/List', [
            'schedule' => $group
        ]);
    }

    public function show($id){
        $schedule = CollectionSchedule::findOrFail($id);

        if(Auth::user()->hasRole('admin')){
            $trucks = Truck::all();
            $routes = Route::all();
        }else{
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
            $routes = Route::where('barangay',  Auth::user()->barangay)->get();
        }

        return Inertia::render('Schedule/View', [
            'Sched' => $schedule,
            'routes' => $routes,
            'trucks' => $trucks
        ]);
    }

    public function edit($id){
        $schedule = CollectionSchedule::findOrFail($id);

        if(Auth::user()->hasRole('admin')){
            $trucks = Truck::all();
            $routes = Route::all();
        }else{
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
            $routes = Route::where('barangay',  Auth::user()->barangay)->get();
        }

        return Inertia::render('Schedule/Edit', [
            'Sched' => $schedule,
            'routes' => $routes,
            'trucks' => $trucks
        ]);
    }

    public function create(){
        if(Auth::user()->hasRole('admin')){
            $trucks = Truck::all();
            $routes = Route::all();
        }else{
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
            $routes = Route::where('barangay',  Auth::user()->barangay)->get();
        }
        return Inertia::render('Schedule/Create', [
            'routes' => $routes,
            'trucks' => $trucks
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'barangay' => 'required|string|max:255',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'truck_id' => 'required|exists:trucks,id',
            'time' => 'required|date_format:H:i',
            'route' => 'required|exists:routes,id'
        ]);

        DB::beginTransaction();
        try{
            $schedule = CollectionSchedule::create([
                'truck_id' => $request->truck_id,
                'day' => $request->day,
                'time' => $request->time,
                'route_id' => $request->route,
                'barangay' => $request->barangay
            ]);

            DB::commit();

            return redirect(route('schedule.calendar'))->with(['message' => 'New Schedule Added.', 'status'=> 'success']);

        }catch(Exception $e){
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function update(Request $request, $id){
        $request->validate([
            'barangay' => 'required|string|max:255',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'truck_id' => 'required|exists:trucks,id',
            'time' => 'required|date_format:H:i',
            'route' => 'required|exists:routes,id'
        ]);

        DB::beginTransaction();
        try{

            $schedule = CollectionSchedule::findOrFail($id);
            
            $schedule->update([
                'truck_id' => $request->truck_id,
                'day' => $request->day,
                'time' => $request->time,
                'route_id' => $request->route,
                'barangay' => $request->barangay
            ]);

            DB::commit();

            return redirect(route('schedule.calendar'))->with(['message' => 'New Schedule Added.', 'status'=> 'success']);

        }catch(Exception $e){
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
