<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'admin',
            'middlename' => '',
            'lastname'=> 'admin',
            'barangay' => 'Plaridel',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password')
        ])->assignRole('admin');
        User::create([
            'firstname' => 'barangay',
            'middlename' => '',
            'lastname'=> 'barangay',
            'barangay' => 'Plaridel',
            'email' => 'barangay@gmail.com',
            'password' => Hash::make('password')
        ])->assignRole('barangay');
    }
}
