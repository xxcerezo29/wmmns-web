<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function list()
    {
        $roles = Role::with('permissions')->get();
        return Inertia::render(
            'RolesAndPermission/Roles/List',
            [
                'roles' => $roles
            ]
        );
    }

    public function create(){
        $permissions = Permission::all();
        return Inertia::render('RolesAndPermission/Roles/Create',[
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:'.Role::class,
            'permissions' => 'array|nullable'
        ]);

        DB::beginTransaction();
        try{
            $role = Role::create([
                'name' => $request->name,
            ]);

            if(!empty($request->permissions)){
                foreach($request->permissions as $permission){
                    $role->givePermissionTo($permission);
                }
            }

            DB::commit();

            return redirect(route('users.roles.list'))->with(['message' => 'New Role Added.', 'status'=> 'success']);
        }catch(Exception $e){

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function edit($id){
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        return Inertia::render('RolesAndPermission/Roles/Update', [
            'role' => $role,
            'permissions'=> $permissions
        ]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
            'permissions' => 'array|nullable'
        ]);

        DB::beginTransaction();
        try{
            $role = Role::findOrFail($id);

            $role->update(['name' => $request->name]);

            if(!empty($request->permissions)){
                $role->syncPermissions($request->permissions);
            }else{
                $role->revokePermissionTo($request->permissions);
            }

            DB::commit();

            return redirect(route('users.roles.list'))->with(['message' => 'Role updated successfully.', 'status'=> 'success']);

        }catch(Exception $e){

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try{

            $role = Role::findOrFail($id);

            $role->delete();

            DB::commit();

            return redirect(route('users.roles.list'))->with(['message' => 'Role deleted successfully.', 'status'=> 'success']);

        }catch(Exception $e){

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
