<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'type' => 'required',
        ]);

        if($request->type === 'resident')
            $user = Resident::where('email', $request->email)->firstOrFail();
        else if($request->type === 'driver')
            $user = Driver::where('email', $request->email)->firstOrFail();

        if(Hash::check($request->password, $user->password)){
            Auth::login($user);
            
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }else{
            return response()->json(['message'=> 'Invalid credentials'], 401);
        }
    }

    public function user(Request $request){
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
