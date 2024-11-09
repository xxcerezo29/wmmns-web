<?php

namespace Database\Factories;

use App\Models\Truck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
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
        $truck = Truck::where('barangay', $barangay)->inRandomOrder()->first();
        if (!$truck) {
            $truck = Truck::factory()->create(['barangay' => $barangay]);
        }


        return [
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->optional()->lastName,
            'lastname' => $this->faker->lastName,
            'barangay' => $barangay,
            'email' => $this->faker->unique()->safeEmail,
            'truck_id' => $truck->id, // Link to a truck with the same barangay
            'mobile_number' => $this->faker->phoneNumber,
            'password' => bcrypt('password'), // Default password
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
        ];
    }
}
