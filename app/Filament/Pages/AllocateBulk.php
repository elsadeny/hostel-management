<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Student;
use App\Services\AllocationService;

class AllocateBulk extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static string $view = 'filament.pages.allocate-bulk';

    protected static ?string $navigationLabel = 'Bulk Allocation';

    protected static ?string $title = 'Allocate Rooms to All Students';

    public function allocateAll()
    {
        $unallocatedStudents = Student::unallocated()->get();

        if ($unallocatedStudents->count() === 0) {
            Notification::make()
                ->title('No unallocated students')
                ->body('All students have already been allocated rooms.')
                ->warning()
                ->send();

            return;
        }

        $service = new AllocationService();
        $academicYear = '2025/2026'; // You can make this dynamic

        $results = $service->batchAllocate($unallocatedStudents, $academicYear);

        Notification::make()
            ->title('Allocation Complete')
            ->body(sprintf(
                'Successfully allocated: %d students. Failed: %d students.',
                count($results['success']),
                count($results['failed'])
            ))
            ->success()
            ->send();

        $this->redirect(static::getUrl());
    }
}
