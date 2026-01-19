<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RoomOccupancyChart extends ChartWidget
{
    protected static ?string $heading = 'Room Occupancy';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $totalCapacity = \App\Models\Room::sum('capacity');
        $currentOccupancy = \App\Models\Room::sum('current_occupancy');
        $available = $totalCapacity - $currentOccupancy;

        return [
            'datasets' => [
                [
                    'label' => 'Occupancy',
                    'data' => [$currentOccupancy, $available],
                    'backgroundColor' => ['#ef4444', '#22c55e'], // Red for occupied, Green for available
                ],
            ],
            'labels' => ['Occupied Beds', 'Available Beds'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
