<?php

namespace App\Filament\Resources\RoomResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RoomStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRooms = \App\Models\Room::count();
        $allocatedRooms = \App\Models\Room::where('current_occupancy', '>', 0)->count();

        // Calculate gender distribution based on active allocations
        $maleAllocations = \App\Models\Allocation::where('status', 'active')
            ->whereHas('student', fn($q) => $q->where('gender', 'male'))
            ->count();

        $femaleAllocations = \App\Models\Allocation::where('status', 'active')
            ->whereHas('student', fn($q) => $q->where('gender', 'female'))
            ->count();

        return [
            Stat::make('Total Rooms', $totalRooms)
                ->description('All available rooms')
                ->descriptionIcon('heroicon-m-home')
                ->color('primary'),

            Stat::make('Allocated Rooms', $allocatedRooms)
                ->description('Rooms with at least one student')
                ->descriptionIcon('heroicon-m-key')
                ->color('success'),

            Stat::make('Male Students Allocated', $maleAllocations)
                ->description('Active male allocations')
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),

            Stat::make('Female Students Allocated', $femaleAllocations)
                ->description('Active female allocations')
                ->descriptionIcon('heroicon-m-user')
                ->color('danger'), // Using danger (red/pink) for distinction
        ];
    }
}
