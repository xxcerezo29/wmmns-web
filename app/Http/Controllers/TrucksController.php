<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TrucksController extends Controller
{
    public function list()
    {
        $user = Auth::user();
        if ($user->hasRole('admin'))
            $trucks = Truck::paginate(10);
        else
            $trucks = Truck::where('barangay', $user->barangay)->paginate(10);
        
            return Inertia::render('Trucks/List', [
                'trucks' => $trucks
            ]);
    }

    public function create(){
        return Inertia::render('Trucks/Create');
    }

    public function edit($id){
        $truck = Truck::findOrFail($id);

        return Inertia::render('Trucks/Update', [
            'truck' => $truck
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'plate_number' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try{
            Truck::create([
                'plate_number' => $request->plate_number,
                'barangay' => $request->barangay,
            ]);

            DB::commit();

            return redirect(route('trucks.list'))->with(['message' => 'New Truck Added.', 'status'=> 'success']);
        }catch(Exception $e){

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function destroy ($id){
        DB::beginTransaction();
        try{
            $truck = Truck::findOrFail($id);

            $truck->delete();

            DB::commit();

            return redirect(route('trucks.list'))->with(['message' => 'Truck Deleted.', 'status'=> 'success']);
        }catch(Exception $e){

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
