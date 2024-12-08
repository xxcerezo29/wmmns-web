<?php

namespace App\Http\Controllers;

use App\Events\NewSchedule;
use App\Models\CollectionSchedule;
use App\Models\Driver;
use App\Models\Resident;
use App\Models\Route;
use App\Models\Truck;
use Artisan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Log;

class GarbageCollectionScheduleController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::user();

        if (!$request->show)
            $request->show = 'false';

        $query = CollectionSchedule::query()
            ->with(['truck', 'route'])
            ->when(!$user->hasRole('admin'), function ($query) use ($user, $request) {
                $query->where('barangay', $user->barangay);

            })
            ->when($user->hasRole('admin'), function ($query) use ($user, $request) {
                if ($request->show === 'false') {
                    $query->where('barangay', 'CENRO');
                }
            })
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
                    'contentFull' => "Detailed schedule:<br>Barangay: {$schedule->barangay}<br>Route: {$schedule->route->name}<br>Truck: {$schedule->truck->plate_number}",
                    'class' => 'collection-schedule',
                ];
            });



        // $paginatedSchedule = $query->paginate(20);

        // $grouped = $paginatedSchedule->getCollection()->groupBy('day');

        // if ($user->hasRole('admin'))
        //     $schedule = CollectionSchedule::with('truck')->with('route')->orderBy('day')->orderBy('time')->paginate(10);
        // else
        //     $schedule = CollectionSchedule::where('barangay', $user->barangay)->with('truck')->with('route')->orderBy('day')->orderBy('time')->paginate(10);

        // $group = $schedule->groupBy('day');

        // $pagination = [
        //     'current_page' => $paginatedSchedule->currentPage(),
        //     'last_page' => $paginatedSchedule->lastPage(),
        //     'next_page_url' => $paginatedSchedule->nextPageUrl(),
        //     'prev_page_url' => $paginatedSchedule->previousPageUrl(),
        //     'links' => $paginatedSchedule->linkCollection()->toArray(), // Provide full pagination links
        // ];

        return Inertia::render('Schedule/List', [
            'schedule' => $query,
            // 'pagination' => $pagination
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
            $routes = Route::where('barangay', Auth::user()->barangay)->get();
        }

        return Inertia::render('Schedule/View', [
            'Sched' => $schedule,
            'routes' => $routes,
            'trucks' => $trucks
        ]);
    }

    public function edit(Request $request, $id)
    {
        $schedule = CollectionSchedule::findOrFail($id);

        if (Auth::user()->hasRole('admin')) {
            if ($request->cenro === 'true') {
                $trucks = Truck::where('barangay', 'CENRO')->get();
                $routes = Route::where('barangay', 'CENRO')->get();
            } else {
                $trucks = Truck::all();
                $routes = Route::all();
            }

        } else {
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
            $routes = Route::where('barangay', Auth::user()->barangay)->get();
        }

        return Inertia::render('Schedule/Edit', [
            'Sched' => $schedule,
            'routes' => $routes,
            'trucks' => $trucks
        ]);
    }

    public function create(Request $request)
    {
        if (Auth::user()->hasRole('admin')) {
            if ($request->cenro === 'true') {
                $trucks = Truck::where('barangay', 'CENRO')->get();
                $routes = Route::where('barangay', 'CENRO')->get();
            } else {
                $trucks = Truck::all();
                $routes = Route::all();
            }

        } else {
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
            $routes = Route::where('barangay', Auth::user()->barangay)->get();
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
            'schedule' => 'required',
            'truck_id' => 'required|exists:trucks,id',
            'time' => 'required|date_format:H:i',
            'route' => 'required|exists:routes,id',
            'cenro' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            if ($request->cenro === true) {
                $schedule = CollectionSchedule::create([
                    'truck_id' => $request->truck_id,
                    'schedule' => $request->schedule,
                    'time' => $request->time,
                    'route_id' => $request->route,
                    'barangay' => 'CENRO',
                    'cenro' => true
                ]);
            } else {
                $schedule = CollectionSchedule::create([
                    'truck_id' => $request->truck_id,
                    'schedule' => $request->schedule,
                    'time' => $request->time,
                    'route_id' => $request->route,
                    'barangay' => $request->barangay,
                    'cenro' => false
                ]);
            }


            DB::commit();

            event(new NewSchedule());

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
            'schedule' => 'required',
            'truck_id' => 'required|exists:trucks,id',
            'time' => 'required|date_format:H:i',
            'route' => 'required|exists:routes,id',
            'cenro' => 'nullable'
        ]);

        DB::beginTransaction();
        try {

            $schedule = CollectionSchedule::findOrFail($id);

            if ($request->cenro === true) {
                $schedule->update([
                    'truck_id' => $request->truck_id,
                    'schedule' => $request->schedule,
                    'time' => $request->time,
                    'route_id' => $request->route,
                    'barangay' => 'CENRO',
                    'cenro' => true
                ]);
            } else {
                $schedule->update([
                    'truck_id' => $request->truck_id,
                    'schedule' => $request->schedule,
                    'time' => $request->time,
                    'route_id' => $request->route,
                    'barangay' => $request->barangay,
                    'cenro' => false
                ]);
            }


            DB::commit();

            return redirect(route('schedule.calendar'))->with(['message' => 'New Schedule Added.', 'status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function downloadPDF()
    {
        try {
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

        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $truck = CollectionSchedule::findOrFail($id);

            $truck->delete();

            DB::commit();

            return redirect(route('schedule.calendar'))->with(['message' => 'Schedule Deleted.', 'status' => 'success']);
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function send(Request $request)
    {
        Artisan::call('app:notify-user-schedule');

        $output = Artisan::output();

        Log::info($output);

        return redirect(route('schedule.calendar'))->with(['message' => $output, 'status' => 'success']);
    }
}
