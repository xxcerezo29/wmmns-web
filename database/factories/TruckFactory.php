<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Truck>
 */
class TruckFactory extends Factory
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

        return [
            'barangay' => $this->faker->randomElement($barangays),
            'plate_number' => strtoupper(Str::random(3)) . '-' . rand(1000, 9999),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
