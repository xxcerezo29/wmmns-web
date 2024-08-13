<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

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
}
