<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Resident;
use App\Notifications\ResetPasswordOtp;
use Exception;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'type' => 'required'
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        
        if($request->type === 'resident')
        {
            $user = Resident::where('email', $request->email)->first();
        }else{
            $user = Driver::where('email', $request->email)->first();
        }

        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $otp = (new Otp)->generate($user->email, 'numeric', 6, 15);

        try{
            Notification::send($user, new ResetPasswordOtp($otp->token));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent to your email.'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
