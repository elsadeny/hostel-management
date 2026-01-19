<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\RoomChangeRequest;

class RoomChangeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $requests = $student->roomChangeRequests()->orderBy('created_at', 'desc')->get();

        return view('student.room-change', compact('student', 'requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        // Check if student has an active allocation
        if (!$student->allocation) {
            return back()->with('error', 'You must have an active room allocation to request a room change.');
        }

        // Check if there's already a pending request
        $pendingRequest = $student->roomChangeRequests()->where('status', 'pending')->first();
        if ($pendingRequest) {
            return back()->with('error', 'You already have a pending room change request.');
        }

        RoomChangeRequest::create([
            'student_id' => $student->id,
            'current_room_id' => $student->allocation->room_id,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Room change request submitted successfully!');
    }
}
