<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentWelcomeMail;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Check if a user with this email already exists
        if (User::where('email', $data['email'])->exists()) {
            Notification::make()
                ->title('Registration Failed')
                ->body('A user account with this email address already exists.')
                ->danger()
                ->send();

            $this->halt();
        }

        $user = User::create([
            'name'     => $data['full_name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        $user->assignRole('student');

        $data['user_id'] = $user->id;

        unset($data['password']);
        unset($data['password_confirmation']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Wrap the entire creation in a transaction so a failed student save
        // automatically rolls back the user creation (no orphaned user accounts).
        return DB::transaction(fn () => parent::handleRecordCreation($data));
    }

    protected function afterCreate(): void
    {
        /** @var \App\Models\Student $student */
        $student = $this->record;

        if ($student->user) {
            try {
                Mail::to($student->user->email)->send(new StudentWelcomeMail($student));
            } catch (\Exception $e) {
                // Email failure is non-critical — student is still registered
            }
        }
    }
}
