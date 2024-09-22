<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Resident;
use Ichtrojan\Otp\Otp;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'type' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->type === 'resident') {
            $user = Resident::where('email', $request->email)->first();
        } else {
            $user = Driver::where('email', $request->email)->first();
        }

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No User found with this email address.'],
            ]);
        }


        $otp = (new Otp)->validate($request->email, $request->token);

        if ($otp->status === true) {
            $user->forceFill([
                'password' => Hash::make($request->password),
            ])->save();

            event(new PasswordReset($user));
        } else {
            throw ValidationException::withMessages([
                'email' => ['This OTP is invalid'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ], 200);
    }
}
