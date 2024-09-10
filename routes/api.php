<?php

use App\Models\Devices;
use App\Models\Driver;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/api/auth.php';
require __DIR__.'/api/text.php';
require __DIR__.'/api/schedule.php';
require __DIR__.'/api/user.php';
require __DIR__.'/api/roam.php';
require __DIR__.'/api/complaint.php';

Route::post('/store/device', function(Request $request){
    $user = Auth()->user();
    $user_devices = $user->devices;
    $isNew = true;

    foreach($user_devices as $key => $devices){
        if($devices->token === $request->token){
            $isNew = false;
            break;
        }
    }

    if($isNew){
        DB::beginTransaction();
        try{
            if($user_devices->count() >= 3){
                $oldDevice = $user_devices->sortBy('created_at')->first();
                $oldDevice->delete();
            }

            if($user instanceof Driver){
                Devices::create([
                    'driver_id' => $user->id,
                    'token' => $request->token->value
                ]);
            }else if($user instanceof Resident){
                Devices::create([
                    'resident_id' => $user->id,
                    'token' => $request->token->value
                ]);
            }else if($user instanceof User){
                Devices::create([
                    'user_id' => $user->id,
                    'token' => $request->token->value
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Device Token Stored.'
            ], 201);
        }
        catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to store token device. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
})->middleware(['auth:sanctum']);