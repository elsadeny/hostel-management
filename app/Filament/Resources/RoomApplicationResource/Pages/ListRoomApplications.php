<?php

namespace App\Filament\Resources\RoomApplicationResource\Pages;

use App\Filament\Resources\RoomApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomApplications extends ListRecords
{
    protected static string $resource = RoomApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'pending' => \Filament\Resources\Components\Tab::make('Pending')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'pending'))
                ->badge(\App\Models\RoomApplication::where('status', 'pending')->count())
                ->badgeColor('warning'),
            'approved' => \Filament\Resources\Components\Tab::make('Approved')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'approved'))
                ->badge(\App\Models\RoomApplication::where('status', 'approved')->count())
                ->badgeColor('success'),
            'rejected' => \Filament\Resources\Components\Tab::make('Rejected')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'rejected'))
                ->badge(\App\Models\RoomApplication::where('status', 'rejected')->count())
                ->badgeColor('danger'),
            'all' => \Filament\Resources\Components\Tab::make('All'),
        ];
    }
}
