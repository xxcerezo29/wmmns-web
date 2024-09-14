<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Truck;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rules;

class DriversController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin'))
            $drivers = Driver::when($request->searchTerm, function ($query, $searchTerm) {
                return $query->where('firstname', 'like', '%' . $searchTerm . '%')
                    ->orWhere('middlename', 'like', '%' . $searchTerm . '%')
                    ->orWhere('lastname', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barangay', 'like', '%' . $searchTerm . '%');
            })->with('AssignedTruck')->paginate(10)->withQueryString();
        else
            $drivers = Driver::with('AssignedTruck')->where('barangay', $user->barangay)->paginate(10);

        return Inertia::render('Drivers/List', [
            'drivers' => $drivers
        ]);
    }

    public function show($id)
    {
        $driver = Driver::with('AssignedTruck')->findOrFail($id);
        try {
            return Inertia::render('Drivers/View', [
                'driver' => $driver
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }


    public function create()
    {
        if (Auth::user()->hasRole('admin')) {
            $trucks = Truck::all();
        } else {
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
        }

        return Inertia::render('Drivers/Create', [
            'trucks' => $trucks
        ]);
    }

    public function edit($id)
    {
        if (Auth::user()->hasRole('admin')) {
            $trucks = Truck::all();
        } else {
            $trucks = Truck::where('barangay', Auth::user()->barangay)->get();
        }

        try {
            $driver = Driver::findOrFail($id);

            return Inertia::render('Drivers/Update', [
                'trucks' => $trucks,
                'driver' => $driver
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:drivers,email,' . $id,
            'truck_id' => 'required',
            'mobile_number' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $barangay = '';
            if (Auth::user()->hasRole('admin')) {
                $barangay = $request->barangay;
            } else {
                $barangay = Auth::user()->barangay;
            }

            $driver = Driver::findOrFail($id);

            $driver->update([
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'barangay' => $barangay,
                'email' => $request->email,
                'truck_id' => $request->truck_id,
                'mobile_number' => $request->mobile_number,
            ]);

            DB::commit();

            return redirect(route('users.drivers.list'))->with(['message' => 'Driver Updated.', 'status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . Driver::class,
            'truck_id' => 'required',
            'mobile_number' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $barangay = '';
            if (Auth::user()->hasRole('admin')) {
                $barangay = $request->barangay;
            } else {
                $barangay = Auth::user()->barangay;
            }

            $driver = Driver::create([
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'barangay' => $barangay,
                'email' => $request->email,
                'truck_id' => $request->truck_id,
                'mobile_number' => $request->mobile_number,
                'password' => Hash::make('password'),
            ]);

            DB::commit();

            return redirect(route('users.drivers.list'))->with(['message' => 'New Driver Added.', 'status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $driver = Driver::findOrFail($id);

            $driver->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function downloadPDF(){
        try{
            $user = Auth::user();
            if ($user->hasRole('admin'))
                $drivers = Driver::with('AssignedTruck')->get();
            else
                $drivers = Driver::where('barangay', $user->barangay)->with('AssignedTruck')->get();
            
            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

            $pdf->loadView('pdf.driverslist', ['drivers' => $drivers]);

            return $pdf->stream('TruckList');

        }catch(Exception $e){
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
