<?php

namespace Database\Seeders;

use App\Models\Company; 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Company::create([
            'name' => 'Test Company A',
            'email' => 'companyA@example.com',
            'phone' => '+33 1 23 45 67 89',
            'address' => '10 rue de la Paix, 75000 Paris',
            'description' => 'A fictional bus company for testing purposes.',
        ]);

    
    }
}