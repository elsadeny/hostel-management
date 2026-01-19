<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', \App\Models\Student::count())
                ->description('Registered students')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Total Rooms', \App\Models\Room::count())
                ->description('Available rooms')
                ->descriptionIcon('heroicon-m-home')
                ->color('info'),

            Stat::make('Active Allocations', \App\Models\Allocation::where('status', 'active')->count())
                ->description('Currently allocated')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('warning'),

            Stat::make('Total Hostels', \App\Models\Hostel::count())
                ->description('Campus hostels')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Occupancy Rate', function () {
                $totalCapacity = \App\Models\Room::sum('capacity');
                $currentOccupancy = \App\Models\Room::sum('current_occupancy');
                if ($totalCapacity == 0)
                    return '0%';
                return round(($currentOccupancy / $totalCapacity) * 100, 1) . '%';
            })
                ->description('Room utilization')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),

            Stat::make('Available Spaces', function () {
                $totalCapacity = \App\Models\Room::sum('capacity');
                $currentOccupancy = \App\Models\Room::sum('current_occupancy');
                return $totalCapacity - $currentOccupancy;
            })
                ->description('Empty bed spaces')
                ->descriptionIcon('heroicon-m-minus-circle')
                ->color('danger'),
        ];
    }
}
