<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\Truck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CollectionSchedule>
 */
class CollectionScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $route = Route::inRandomOrder()->first();
        $truck = Truck::where('barangay', $route->barangay)->inRandomOrder()->first();
        if (!$truck) {
            $truck = Truck::factory()->create(['barangay' => $route->barangay]);
        }
        $time = $this->faker->time('H:i:s');
        $date = $this->faker->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d');
        return [
            'barangay' => $route->barangay,
            'truck_id' => $truck->id,
            'route_id' => $route->id,
            'time' => $time, // New time field
            'schedule' => $date,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
