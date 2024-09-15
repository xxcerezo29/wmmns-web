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
    public function list()
    {
        $user = Auth::user();

        $query  = CollectionSchedule::query()
            ->with('truck', 'route')
            ->when(!$user->hasRole('admin'), function ($query) use ($user) {
                $query->where('barangay', $user->barangay);
            })
            ->orderBy('day')
            ->orderBy('time');

        $paginatedSchedule = $query->paginate(20);

        $grouped = $paginatedSchedule->getCollection()->groupBy('day');

        // if ($user->hasRole('admin'))
        //     $schedule = CollectionSchedule::with('truck')->with('route')->orderBy('day')->orderBy('time')->paginate(10);
        // else
        //     $schedule = CollectionSchedule::where('barangay', $user->barangay)->with('truck')->with('route')->orderBy('day')->orderBy('time')->paginate(10);

        // $group = $schedule->groupBy('day');

        $pagination = [
            'current_page' => $paginatedSchedule->currentPage(),
            'last_page' => $paginatedSchedule->lastPage(),
            'next_page_url' => $paginatedSchedule->nextPageUrl(),
            'prev_page_url' => $paginatedSchedule->previousPageUrl(),
            'links' => $paginatedSchedule->linkCollection()->toArray(), // Provide full pagination links
        ];

        return Inertia::render('Schedule/List', [
            'schedule' => $grouped,
            'pagination' => $pagination
        ]);
    }

    public function show($id)
    {
        $schedule = CollectionSchedule::findOrFail($id);

        if (Auth::user()->hasRole('admin')) {
            $trucks = Truck::all();
            $routes = Route::all();
        } else {
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
            $routes = Route::where('barangay',  Auth::user()->barangay)->get();
        }

        return Inertia::render('Schedule/View', [
            'Sched' => $schedule,
            'routes' => $routes,
            'trucks' => $trucks
        ]);
    }

    public function edit($id)
    {
        $schedule = CollectionSchedule::findOrFail($id);

        if (Auth::user()->hasRole('admin')) {
            $trucks = Truck::all();
            $routes = Route::all();
        } else {
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
            $routes = Route::where('barangay',  Auth::user()->barangay)->get();
        }

        return Inertia::render('Schedule/Edit', [
            'Sched' => $schedule,
            'routes' => $routes,
            'trucks' => $trucks
        ]);
    }

    public function create()
    {
        if (Auth::user()->hasRole('admin')) {
            $trucks = Truck::all();
            $routes = Route::all();
        } else {
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
            $routes = Route::where('barangay',  Auth::user()->barangay)->get();
        }
        return Inertia::render('Schedule/Create', [
            'routes' => $routes,
            'trucks' => $trucks
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barangay' => 'required|string|max:255',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'truck_id' => 'required|exists:trucks,id',
            'time' => 'required|date_format:H:i',
            'route' => 'required|exists:routes,id'
        ]);

        DB::beginTransaction();
        try {
            $schedule = CollectionSchedule::create([
                'truck_id' => $request->truck_id,
                'day' => $request->day,
                'time' => $request->time,
                'route_id' => $request->route,
                'barangay' => $request->barangay
            ]);

            DB::commit();

            return redirect(route('schedule.calendar'))->with(['message' => 'New Schedule Added.', 'status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barangay' => 'required|string|max:255',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'truck_id' => 'required|exists:trucks,id',
            'time' => 'required|date_format:H:i',
            'route' => 'required|exists:routes,id'
        ]);

        DB::beginTransaction();
        try {

            $schedule = CollectionSchedule::findOrFail($id);

            $schedule->update([
                'truck_id' => $request->truck_id,
                'day' => $request->day,
                'time' => $request->time,
                'route_id' => $request->route,
                'barangay' => $request->barangay
            ]);

            DB::commit();

            return redirect(route('schedule.calendar'))->with(['message' => 'New Schedule Added.', 'status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function downloadPDF(){
        try{
            $user = Auth::user();
            if ($user->hasRole('admin'))
                $schedules = CollectionSchedule::with('truck')->with('route')->get();
            else
                $schedules = CollectionSchedule::with('truck')->with('route')->where('barangay', $user->barangay)->get();
            
            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

            $pdf->loadView('pdf.schedule', ['schedules' => $schedules]);

            return $pdf->download('schedule.pdf');

        }catch(Exception $e){
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
