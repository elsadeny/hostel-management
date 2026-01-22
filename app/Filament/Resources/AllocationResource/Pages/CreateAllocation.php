<?php

namespace App\Filament\Resources\AllocationResource\Pages;

use App\Filament\Resources\AllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Mail;
use App\Mail\AllocationNotificationMail;

class CreateAllocation extends CreateRecord
{
    protected static string $resource = AllocationResource::class;

    protected function afterCreate(): void
    {
        /** @var \App\Models\Allocation $allocation */
        $allocation = $this->record;

        if ($allocation->student && $allocation->student->user) {
            try {
                Mail::to($allocation->student->user->email)->send(new AllocationNotificationMail($allocation));
            } catch (\Exception $e) {
                // Log error or notify admin, but don't fail the request
            }
        }
    }
}
