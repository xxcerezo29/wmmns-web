<?php

namespace Database\Factories;

use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
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

        return [
            'firstname' => $this->faker->firstName(),
            'middlename' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'line1' => $this->faker->address(),
            'line2' => $this->faker->address(),
            'barangay' => $barangay,
            'city' => 'City of Santiago',
            'province' => 'Isabela',
            'country' => 'Philippines',
            'email' => $this->faker->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ];
    }
}
