<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TrucksController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            if ($request->show === "true") {
                $trucks = Truck::when($request->searchTerm, function ($query, $searchTerm) {
                    return $query->where('plate_number', 'like', '%' . $searchTerm . '%');
                })->paginate(10)->withQueryString();
            } else {
                $trucks = Truck::when($request->searchTerm, function ($query, $searchTerm) {
                    return $query->where('plate_number', 'like', '%' . $searchTerm . '%');
                })->where('barangay', 'CENRO')->paginate(10)->withQueryString();

            }
        } else {
            $trucks = Truck::when($request->searchTerm, function ($query, $searchTerm) {
                return $query->where('plate_number', 'like', '%' . $searchTerm . '%');
            })->where('barangay', $user->barangay)->paginate(10)->withQueryString();
        }

        return Inertia::render('Trucks/List', [
            'trucks' => $trucks,
            'show' => $request->show
        ]);
    }

    public function show($id)
    {
        $truck = Truck::with('driver')->findOrFail($id);
        try {
            return Inertia::render('Trucks/View', [
                'truck' => $truck
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function create()
    {
        return Inertia::render('Trucks/Create');
    }

    public function edit($id)
    {
        $truck = Truck::findOrFail($id);

        return Inertia::render('Trucks/Update', [
            'truck' => $truck
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'cenro' => 'nullable'
        ]);

        DB::beginTransaction();

        try {
            if ($request->cenro === true) {
                Truck::create([
                    'plate_number' => $request->plate_number,
                    'barangay' => 'CENRO',
                    'cenro' => true
                ]);
            } else {
                Truck::create([
                    'plate_number' => $request->plate_number,
                    'barangay' => $request->barangay,
                    'cenro' => false
                ]);
            }


            DB::commit();

            return redirect(route('trucks.list'))->with(['message' => 'New Truck Added.', 'status' => 'success']);
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function update(Request $request, Truck $truck)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'cenro' => 'nullable'
        ]);

        DB::beginTransaction();

        try {
            if ($request->cenro === true) {
                $truck->update([
                    'plate_number' => $request->plate_number,
                    'barangay' => 'CENRO',
                    'cenro' => true
                ]);
            } else {
                $truck->update([
                    'plate_number' => $request->plate_number,
                    'barangay' => $request->barangay,
                    'cenro' => false
                ]);
            }

            DB::commit();

            return redirect(route('trucks.list'))->with(['message' => 'Update Truck Added.', 'status' => 'success']);
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $truck = Truck::findOrFail($id);

            $truck->delete();

            DB::commit();

            return redirect(route('trucks.list'))->with(['message' => 'Truck Deleted.', 'status' => 'success']);
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
                $trucks = Truck::all();
            else
                $trucks = Truck::where('barangay', $user->barangay)->all();

            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

            $pdf->loadView('pdf.trucklist', ['trucks' => $trucks]);

            return $pdf->download('TruckList.pdf');

        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
