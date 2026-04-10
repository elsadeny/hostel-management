<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentWelcomeMail;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'name' => $data['full_name'],
            'email' => $data['email'],
            'password' => $data['password'], // User model auto-hashes via 'hashed' cast
        ]);

        $user->assignRole('student');

        $data['user_id'] = $user->id;

        unset($data['password']);
        unset($data['password_confirmation']);

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var \App\Models\Student $student */
        $student = $this->record;

        if ($student->user) {
            try {
                Mail::to($student->user->email)->send(new StudentWelcomeMail($student));
            } catch (\Exception $e) {
                // Log error or notify admin, but don't fail the request
            }
        }
    }
}
