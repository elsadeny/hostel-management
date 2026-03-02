<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\RoomApplication;

class RoomApplicationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return view('student.no-profile');
        }

        // Students who already have an active allocation don't need to apply
        if ($student->allocation) {
            return redirect()->route('student.dashboard')
                ->with('info', 'You already have an active room allocation.');
        }

        // Fetch hostels matching the student's gender for display
        $hostels = Hostel::where('gender', $student->gender)
            ->where('status', 'active')
            ->get();

        // Previous applications by this student
        $applications = RoomApplication::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingApplication = $applications->where('status', 'pending')->first();

        $currentYear = date('Y');
        $academicYear = $currentYear . '/' . ($currentYear + 1);

        return view('student.room-application', compact(
            'student',
            'hostels',
            'applications',
            'pendingApplication',
            'academicYear'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        // Guard: already allocated
        if ($student->allocation) {
            return back()->with('error', 'You already have an active room allocation.');
        }

        // Guard: already has a pending application
        $existing = RoomApplication::where('student_id', $student->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending application. Please wait for it to be processed.');
        }

        $validated = $request->validate([
            'preferred_hostel' => 'nullable|string|max:255',
            'room_type' => 'nullable|string|max:100',
            'special_needs' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ]);

        $currentYear = date('Y');

        RoomApplication::create([
            'student_id' => $student->id,
            'preferred_hostel' => $validated['preferred_hostel'] ?? null,
            'room_type' => $validated['room_type'] ?? null,
            'special_needs' => $validated['special_needs'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'academic_year' => $currentYear . '/' . ($currentYear + 1),
        ]);

        return back()->with('success', 'Your room application has been submitted successfully! The administration will review it shortly.');
    }

    public function cancel(RoomApplication $application)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        if ($application->student_id !== $student->id) {
            abort(403);
        }

        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be cancelled.');
        }

        $application->update(['status' => 'cancelled']);

        return back()->with('success', 'Application cancelled successfully.');
    }
}
