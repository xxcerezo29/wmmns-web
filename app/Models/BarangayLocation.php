<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay',
        'lat',
        'long'
    ];
}
