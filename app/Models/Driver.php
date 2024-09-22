<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'barangay',
        'email',
        'truck_id',
        'mobile_number',
        'password'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function AssignedTruck (){
        return $this->hasOne(Truck::class, 'id', 'truck_id');
    }

    public function devices()
    {
        return $this->hasMany(Devices::class, 'driver_id', 'id');
    }
}
