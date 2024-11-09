<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RoutePlanController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $routes = Route::when($request->searchTerm, function ($query, $searchTerm) {
                return $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barangay', 'like', '%' . $searchTerm . '%');
            })->paginate(10)->withQueryString();
        } else {
            $routes = Route::when($request->searchTerm, function ($query, $searchTerm) {
                return $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barangay', 'like', '%' . $searchTerm . '%');
            })->where('barangay', $user->barangay)->paginate(10)->withQueryString();
        }

        return Inertia::render('Routes/List', [
            'routes' => $routes
        ]);
    }

    public function create()
    {
        return Inertia::render('Routes/Create');
    }
    public function edit($id)
    {
        $route = Route::findOrFail($id);
        return Inertia::render('Routes/Edit', [
            '_route' => $route
        ]);
    }
    public function show($id)
    {
        $route = Route::findOrFail($id);
        try {
            return Inertia::render('Routes/View', [
                'route' => $route
            ]);

        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'waypoint' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $route = Route::create([
                'name' => $request->name,
                'barangay' => $request->barangay,
                'waypoint' => json_encode($request->waypoint)
            ]);

            DB::commit();

            return redirect(route('routes.list'))->with(['message' => 'New Route Added.', 'status' => 'success']);

        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'waypoint' => 'required|array'
        ]);

        $route = Route::findOrFail($id);

        DB::beginTransaction();
        try {



            $route->update([
                'name' => $request->name,
                'barangay' => $request->barangay,
                'waypoint' => json_encode($request->waypoint)
            ]);

            DB::commit();

            return redirect(route('routes.list'))->with(['message' => 'Route Updated.', 'status' => 'success']);

        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function destroy($id)
    {

        $route = Route::findOrFail($id);

        DB::beginTransaction();
        try {

            $route->delete();

            DB::commit();

            return redirect(route('routes.list'))->with(['message' => 'Route Delete.', 'status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
