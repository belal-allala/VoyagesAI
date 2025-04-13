<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bus::create([
            'departure_city' => 'Paris',
            'destination_city' => 'Lyon',
            'departure_time' => '2024-09-15 08:00:00',
            'price' => 25.00,
            'capacity' => 50,
            'available_seats' => 30,
            'company_name' => 'Bus Company A',
        ]);

        Bus::create([
            'departure_city' => 'Marseille',
            'destination_city' => 'Bordeaux',
            'departure_time' => '2024-09-16 14:30:00',
            'price' => 30.50,
            'capacity' => 45,
            'available_seats' => 25,
            'company_name' => 'Bus Company B',
        ]);
    }
}