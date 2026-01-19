<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hostel;

class HostelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hostels = [
            [
                'name' => 'Victoria Hall',
                'gender' => 'female',
                'total_rooms' => 30,
                'total_capacity' => 120,
                'address' => 'Block A, University Campus',
                'description' => 'Modern female hostel with 4-bed rooms',
                'status' => 'active',
            ],
            [
                'name' => 'Kings Hall',
                'gender' => 'male',
                'total_rooms' => 35,
                'total_capacity' => 140,
                'address' => 'Block B, University Campus',
                'description' => 'Male hostel with spacious rooms',
                'status' => 'active',
            ],
            [
                'name' => 'Queens Residence',
                'gender' => 'female',
                'total_rooms' => 25,
                'total_capacity' => 100,
                'address' => 'Block C, University Campus',
                'description' => 'Premium female accommodation',
                'status' => 'active',
            ],
            [
                'name' => 'Unity Hall',
                'gender' => 'mixed',
                'total_rooms' => 40,
                'total_capacity' => 160,
                'address' => 'Block D, University Campus',
                'description' => 'Mixed hostel with separate floors',
                'status' => 'active',
            ],
            [
                'name' => 'Heritage Hall',
                'gender' => 'male',
                'total_rooms' => 30,
                'total_capacity' => 120,
                'address' => 'Block E, University Campus',
                'description' => 'Traditional male hostel',
                'status' => 'active',
            ],
        ];

        foreach ($hostels as $hostel) {
            Hostel::create($hostel);
        }
    }
}
