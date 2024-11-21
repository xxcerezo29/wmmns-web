<?php

namespace Database\Factories;

use App\Models\CollectionSchedule;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resident = Resident::inRandomOrder()->first();
        $schedule = CollectionSchedule::inRandomOrder()->first();

        $type = [
            'missed_collection',
            'illegal_dumping'
        ];

        $status = [
            'closed',
            'pending',
            'reviewed',
            'resolved'
        ];

        $statusss = $this->faker->randomElement($status);
        return [
            'reference_number' => 'RPT-' . strtoupper(uniqid()),
            'resident_id' => $resident->id,
            'schedule_id' => $schedule->id,
            'report_type' => $this->faker->randomElement($type),
            'barangay' => $resident->barangay,
            'description' => $this->faker->sentence,
            'status' => $statusss,
            'resolved_at' => $statusss === 'resolved' ? now() : null
        ];
    }
}
