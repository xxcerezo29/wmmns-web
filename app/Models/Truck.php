<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay',
        'plate_number',
        'cenro'
    ];

    public function schedule()
    {
        return $this->hasMany(CollectionSchedule::class, 'truck_id', 'id');
    }

    public function driver()
    {
        return $this->hasOne(Driver::class, 'truck_id', 'id');
    }

}
