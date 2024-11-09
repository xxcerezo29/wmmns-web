<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Route>
 */
class RouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $barangays = [
            "Abra",
            "Ambalatungan",
            "Balintocatoc",
            "Baluarte",
            "Bannawag Norte",
            "Batal",
            "Buenavista",
            "Cabulay",
            "Calao East (Pob.)",
            "Calao West (Pob.)",
            "Calaocan",
            "Villa Gonzaga",
            "Centro East (Pob.)",
            "Centro West (Pob.)",
            "Divisoria",
            "Dubinan East",
            "Dubinan West",
            "Luna",
            "Mabini",
            "Malvar",
            "Nabbuan",
            "Naggasican",
            "Patul",
            "Plaridel",
            "Rizal",
            "Rosario",
            "Sagana",
            "Salvador",
            "San Andres",
            "San Isidro",
            "San Jose",
            "Sinili",
            "Sinsayon",
            "Santa Rosa",
            "Victory Norte",
            "Victory Sur",
            "Villasis"
        ];
        $barangay = $this->faker->randomElement($barangays);

        $waypoints = collect(range(1, 5))->map(function () {
            return [
                'lat' => $this->faker->latitude(16.685, 16.690), // Latitude range for Santiago City
                'lng' => $this->faker->longitude(121.545, 121.555) // Longitude range for Santiago City
            ];
        });
        return [
            'barangay' => $barangay,
            'waypoint' => $waypoints->toJson(),
            'name' => 'Route to ' . $barangay,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
