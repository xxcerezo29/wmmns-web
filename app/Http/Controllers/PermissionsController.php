<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function list()
    {
        $permissions = Permission::all();
        return Inertia::render(
            'RolesAndPermission/Permissions/List',
            [
                'permissions' => $permissions
            ]
        );
    }

    public function create()
    {
        return Inertia::render('RolesAndPermission/Permissions/Create');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return Inertia::render('RolesAndPermission/Permissions/Update', [
            'permission' => $permission
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        DB::beginTransaction();
        try {
            $permission = Permission::findOrFail($id);

            $permission->update([
                'name' => $request->name
            ]);

            DB::commit();

            return redirect(route('users.permissions.list'))->with(['message' => 'Permission Updated.', 'status' => 'success']);
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:' . Permission::class,
        ]);

        DB::beginTransaction();
        try {
            Permission::create([
                'name' => $request->name,
            ]);

            DB::commit();

            return redirect(route('users.permissions.list'))->with(['message' => 'New Permission Added.', 'status' => 'success']);
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $permission = Permission::findOrFail($id);

            $permission->delete();
            
            DB::commit();

            return redirect(route('users.permissions.list'))->with(['message' => 'Permission Deleted.', 'status' => 'success']);
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
