<?php

namespace App\Filament\Resources\RoomChangeRequestResource\Pages;

use App\Filament\Resources\RoomChangeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomChangeRequest extends EditRecord
{
    protected static string $resource = RoomChangeRequestResource::class;

    public ?string $newRoomId = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['new_room_id'])) {
            $this->newRoomId = $data['new_room_id'];
            unset($data['new_room_id']);
        }

        // Auto-fill processed_by and processed_at if status changes to approved/rejected
        if ($data['status'] !== 'pending' && $this->record->status === 'pending') {
            $data['processed_by'] = auth()->id();
            $data['processed_at'] = now();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        if ($record->status === 'approved' && $this->newRoomId) {
            $student = $record->student;
            $currentAllocation = $student->allocation;

            if ($currentAllocation && $currentAllocation->room_id == $record->current_room_id) {
                // Perform the room change
                $oldRoom = $currentAllocation->room;
                $newRoom = \App\Models\Room::find($this->newRoomId);

                if ($newRoom && $newRoom->status === 'available') {
                    // Update occupancy
                    $oldRoom->removeOccupant();
                    $newRoom->addOccupant();

                    // Update allocation
                    $currentAllocation->update([
                        'room_id' => $newRoom->id,
                        'hostel_id' => $newRoom->hostel_id, // Also update hostel if different
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Room Changed Successfully')
                        ->body("Student moved from Room {$oldRoom->room_number} to Room {$newRoom->room_number}.")
                        ->send();
                } else {
                    \Filament\Notifications\Notification::make()
                        ->danger()
                        ->title('Room Change Failed')
                        ->body('The selected new room is not available.')
                        ->send();
                }
            } else {
                \Filament\Notifications\Notification::make()
                    ->warning()
                    ->title('Allocation Mismatch')
                    ->body('The student is no longer in the room specified in the request. Allocation was NOT updated.')
                    ->send();
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
