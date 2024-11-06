<?php

namespace App\Http\Controllers;

use App\Mail\UserPasswordMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rules;
use Mail;
use Spatie\Permission\Models\Role;

use Str;
use function PHPUnit\Framework\isEmpty;

class UsersController extends Controller
{
    public function list(Request $request)
    {

        $users = User::with('roles')->when($request->searchTerm, function ($query, $searchTerm) {
            return $query->where('firstname', 'like', '%' . $searchTerm . '%')
                ->orWhere('middlename', 'like', '%' . $searchTerm . '%')
                ->orWhere('lastname', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('barangay', 'like', '%' . $searchTerm . '%');
        })->paginate(10)->withQueryString();

        return Inertia::render('Users/List', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        return Inertia::render('Users/Create', [
            'roles' => $roles
        ]);
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        try {
            return Inertia::render('Users/View', [
                'user' => $user
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }


    public function edit($id)
    {
        $roles = Role::all();
        $user = User::findOrFail($id);
        return Inertia::render('Users/Update', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $id,
            'roles' => 'nullable|array'
        ]);

        DB::beginTransaction();

        try {

            $user = User::findOrFail($id);

            $user->update([
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'barangay' => $request->barangay,
                'email' => $request->email,
            ]);

            if (!empty($request->roles)) {
                $user->syncRoles($request->roles);
            } else {
                $user->syncRoles([]);
            }

            DB::commit();

            return redirect(route('users.all.list'))->with(['message' => 'User Updated.', 'status' => 'success']);
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
            'barangay' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'roles' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {

            $generatedPassword = Str::random(12);

            $user = User::create([
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'barangay' => $request->barangay,
                'email' => $request->email,
                'password' => Hash::make($generatedPassword),
            ]);


            if (isset($request->roles) && !empty($request->roles)) {
                $user->assignRole($request->roles);
            }

            Mail::to($user->email)->send(new UserPasswordMail($user, $generatedPassword));

            DB::commit();

            return redirect(route('users.all.list'))->with(['message' => 'New User Added.', 'status' => 'success']);
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $user = User::findOrFail($id);

            if (Auth::user()->id === $id)
                throw new Exception("Can't Delete this user");

            $user->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('users.all.list'))->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function downloadPDF()
    {
        try {
            $users = User::with('roles')->get();

            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

            $pdf->loadView('pdf.userslist', ['users' => $users]);

            return $pdf->download('UserList');
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
