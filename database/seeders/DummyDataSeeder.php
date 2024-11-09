<?php

namespace Database\Seeders;

use App\Models\CollectionSchedule;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Truck;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Truck::factory()->count(100)->create();
        Driver::factory()->count(100)->create();
        Route::factory()->count(100)->create();
        CollectionSchedule::factory()->count(100)->create();
    }
}
