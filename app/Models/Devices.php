<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'driver_id',
        'user_id',
        'token'
    ];

    public function resident() {
        return $this->hasOne(Resident::class, 'id', 'resident_id');
    }
    public function driver() {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
