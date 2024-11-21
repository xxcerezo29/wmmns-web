<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Resident;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Resident::factory()->count(200)->create();
        Report::factory()->count(200)->create();
    }
}
