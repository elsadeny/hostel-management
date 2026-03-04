<?php

namespace App\Filament\Resources\RoomApplicationResource\Pages;

use App\Filament\Resources\RoomApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomApplication extends EditRecord
{
    protected static string $resource = RoomApplicationResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Auto-fill processed_by and processed_at when status changes from pending
        if (
            isset($data['status']) &&
            $data['status'] !== 'pending' &&
            $this->record->status === 'pending'
        ) {
            $data['processed_by'] = auth()->id();
            $data['processed_at'] = now();
        }

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Application updated successfully';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
