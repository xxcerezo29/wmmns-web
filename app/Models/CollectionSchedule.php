<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'day',
        'time',
        'route_id',
        'barangay',
        'schedule'
    ];

    public function truck()
    {
        return $this->hasOne(Truck::class, 'id', 'truck_id');
    }
    public function route()
    {
        return $this->hasOne(Route::class, 'id', 'route_id');
    }
}
