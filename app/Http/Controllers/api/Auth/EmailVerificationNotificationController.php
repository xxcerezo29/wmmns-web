<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Already Verified.'
            ], 200);
        }

        $otp = (new Otp)->generate($request->user()->email, 'numeric', 6, 15);

        try {
            Mail::raw("Your verification code is: $otp->token", function ($message) use ($request) {
                $message->to($request->user()->email)
                    ->subject('Email Verification Code');
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Verification Sent.'
            ]);
        } catch (Exception $e) {
            Log::error('Failed to send email verification code: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send verification code.'
            ], 500);
        }
    }
}
