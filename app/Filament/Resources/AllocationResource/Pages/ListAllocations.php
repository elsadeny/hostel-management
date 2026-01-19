<?php

namespace App\Filament\Resources\AllocationResource\Pages;

use App\Filament\Resources\AllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllocations extends ListRecords
{
    protected static string $resource = AllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('allocateUnallocated')
                ->label('Allocate Unallocated Students')
                ->color('success')
                ->icon('heroicon-o-user-plus')
                ->requiresConfirmation()
                ->modalHeading('Allocate Unallocated Students')
                ->modalDescription('This will allocate rooms to all students who do not currently have a room. Existing allocations will be preserved.')
                ->action(function () {
                    $this->runAllocation(\App\Models\Student::doesntHave('allocation')->get());
                }),

            Actions\Action::make('reallocateAll')
                ->label('Re-allocate All Students')
                ->color('danger')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading('Re-allocate ALL Students')
                ->modalDescription('WARNING: This will DELETE ALL existing allocations and reset room occupancy. All students will be re-allocated from scratch. This action cannot be undone.')
                ->action(function () {
                    // 1. Clear all allocations
                    \App\Models\Allocation::truncate();

                    // 2. Reset room occupancy
                    \App\Models\Room::query()->update([
                        'current_occupancy' => 0,
                        'status' => 'available'
                    ]);

                    // 3. Run allocation for ALL students
                    $this->runAllocation(\App\Models\Student::all());
                }),
        ];
    }

    protected function runAllocation($students)
    {
        $count = 0;

        foreach ($students as $student) {
            // Priority 1: Find partially filled rooms (occupancy > 0 AND < capacity) matching gender
            $room = \App\Models\Room::where('status', 'available')
                ->where('current_occupancy', '>', 0)
                ->whereColumn('current_occupancy', '<', 'capacity')
                ->whereHas('allocations', function ($query) use ($student) {
                    $query->whereHas('student', function ($q) use ($student) {
                        $q->where('gender', $student->gender);
                    });
                })
                ->first();

            // Priority 2: If no partially filled room, find any available empty room
            if (!$room) {
                $room = \App\Models\Room::where('status', 'available')
                    ->where('current_occupancy', 0)
                    ->whereHas('hostel', function ($q) use ($student) {
                        $q->whereIn('gender', [strtolower($student->gender), 'mixed']);
                    })
                    ->first();
            }

            if ($room) {
                \App\Models\Allocation::create([
                    'student_id' => $student->id,
                    'room_id' => $room->id,
                    'hostel_id' => $room->hostel_id,
                    'allocation_date' => now(),
                    'status' => 'active',
                    'allocation_type' => 'auto',
                    'academic_year' => date('Y') . '-' . (date('Y') + 1),
                ]);

                $room->addOccupant();
                $count++;
            }
        }

        if ($count > 0) {
            \Filament\Notifications\Notification::make()
                ->success()
                ->title('Allocation Complete')
                ->body("Successfully allocated rooms to {$count} students.")
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->warning()
                ->title('No Allocations Made')
                ->body('No suitable rooms found or no students needed allocation.')
                ->send();
        }
    }
}
