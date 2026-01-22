<?php

namespace Tests\Feature;

use App\Mail\AllocationNotificationMail;
use App\Mail\StudentWelcomeMail;
use App\Models\Allocation;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_welcome_mail_content()
    {
        $user = User::factory()->create();
        $student = Student::create([
            'user_id' => $user->id,
            'student_id' => '12345',
            'full_name' => 'John Doe',
            'gender' => 'male',
            'study_level' => 'undergraduate',
            'department' => 'Computer Science',
            'year' => 1,
            'phone' => '1234567890',
            'email' => 'john@example.com',
        ]);

        $mail = new StudentWelcomeMail($student);

        $mail->assertSeeInHtml('Welcome, John Doe!');
        $mail->assertSeeInHtml('12345');
        $mail->assertSeeInHtml('Computer Science');
    }

    public function test_allocation_notification_mail_content()
    {
        $user = User::factory()->create();
        $student = Student::create([
            'user_id' => $user->id,
            'student_id' => '12345',
            'full_name' => 'Jane Doe',
            'gender' => 'female',
            'study_level' => 'undergraduate',
            'department' => 'Engineering',
            'year' => 2,
            'phone' => '0987654321',
            'email' => 'jane@example.com',
        ]);

        $hostel = Hostel::create([
            'name' => 'Hostel A',
            'gender' => 'female',
            'capacity' => 100,
            'description' => 'Test Hostel',
            'location' => 'Campus',
            'type' => 'standard',
            'total_rooms' => 50,
            'total_capacity' => 100,
            'address' => '123 Main St',
        ]);

        $room = Room::create([
            'hostel_id' => $hostel->id,
            'room_number' => '101',
            'floor' => 1,
            'capacity' => 2,
            'price' => 5000,
            'status' => 'available',
        ]);

        $allocation = Allocation::create([
            'student_id' => $student->id,
            'room_id' => $room->id,
            'hostel_id' => $hostel->id,
            'allocation_date' => now(),
            'status' => 'active',
            'allocation_type' => 'manual',
            'academic_year' => '2023-2024',
        ]);

        $mail = new AllocationNotificationMail($allocation);

        $mail->assertSeeInHtml('Hello, Jane Doe!');
        $mail->assertSeeInHtml('Hostel A');
        $mail->assertSeeInHtml('101');
    }
}
