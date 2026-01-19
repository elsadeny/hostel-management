<?php

namespace App\Filament\Resources\RoomChangeRequestResource\Pages;

use App\Filament\Resources\RoomChangeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomChangeRequests extends ListRecords
{
    protected static string $resource = RoomChangeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'pending' => \Filament\Resources\Components\Tab::make('Pending Requests')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'pending'))
                ->badge(\App\Models\RoomChangeRequest::where('status', 'pending')->count())
                ->badgeColor('warning'),
            'history' => \Filament\Resources\Components\Tab::make('History')
                ->modifyQueryUsing(fn($query) => $query->whereIn('status', ['approved', 'rejected'])),
        ];
    }
}
