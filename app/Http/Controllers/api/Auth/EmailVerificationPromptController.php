<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {

        $user = $request->user();

        if($user->hasVerifiedEmail()){
            return response()->json([
                'status' => 'success',
                'message' => 'Email already verified',
            ], 200);
        }

        $otp = (new Otp)->validate($user->email, $request->code);

        if($otp->status === true){
            return response()->json([
                'status' => 'success',
                'message' => 'Email is verified.',
            ], 200);
        }else{

        }

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
