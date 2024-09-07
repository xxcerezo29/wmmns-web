<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'resident_id',
        'schedule_id',
        'report_type',
        'location',
        'description',
        'barangay',
        'status',
        'photo_url',
        'resolved_at',
    ];

    public function resident() {
        return $this->hasOne(Resident::class, 'id', 'resident_id');
    }
    public function schedule() {
        return $this->hasOne(CollectionSchedule::class, 'id', 'schedule_id');
    }
    
}
