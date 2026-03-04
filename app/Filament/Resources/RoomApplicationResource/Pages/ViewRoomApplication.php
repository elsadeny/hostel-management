<?php

namespace App\Filament\Resources\RoomApplicationResource\Pages;

use App\Filament\Resources\RoomApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRoomApplication extends ViewRecord
{
    protected static string $resource = RoomApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
