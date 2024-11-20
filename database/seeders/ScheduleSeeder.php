<?php

namespace Database\Seeders;

use App\Models\CollectionSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CollectionSchedule::factory()->count(100)->create();
    }
}
