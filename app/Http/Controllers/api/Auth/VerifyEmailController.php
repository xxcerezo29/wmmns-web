<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use Ichtrojan\Otp\Otp;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request)
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

            if($request->user()->markEmailAsVerified()){
                event(new Verified($request->user()));

                return response()->json([
                    'status' => 'success',
                    'message' => 'Email is verified.',
                ], 200);
            }   
        } else{
            return response()->json([
                'status' => 'error',
                'message' => $otp->message,
            ], 500);
        }

       
    }
}
