<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Hostel;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hostels = Hostel::all();

        foreach ($hostels as $hostel) {
            $roomsPerFloor = ceil($hostel->total_rooms / 3); // 3 floors
            $capacityPerRoom = (int) ceil($hostel->total_capacity / $hostel->total_rooms);

            for ($floor = 1; $floor <= 3; $floor++) {
                $roomsOnFloor = $roomsPerFloor;

                // Adjust for the last floor to match total_rooms exactly
                if ($floor == 3) {
                    $roomsOnFloor = $hostel->total_rooms - (($floor - 1) * $roomsPerFloor);
                }

                // Room numbering starts from 1 on each floor
                for ($roomNum = 1; $roomNum <= $roomsOnFloor; $roomNum++) {
                    Room::create([
                        'hostel_id' => $hostel->id,
                        // Format: {hostel_id}-{floor}{room_number}
                        // Example: 1-205 = Hostel 1, Floor 2, Room 05
                        'room_number' => sprintf('%d-%d%02d', $hostel->id, $floor, $roomNum),
                        'capacity' => $capacityPerRoom,
                        'current_occupancy' => 0,
                        'floor' => $floor,
                        'status' => 'available',
                    ]);
                }
            }
        }
    }
}
