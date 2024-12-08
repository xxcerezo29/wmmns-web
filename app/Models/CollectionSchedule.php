<?php

namespace App\Models;

use App\Observers\V1\ScheduleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(ScheduleObserver::class)]
class CollectionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'day',
        'time',
        'route_id',
        'barangay',
        'schedule',
        'cenro'
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
