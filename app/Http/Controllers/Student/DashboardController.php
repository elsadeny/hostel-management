<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Allocation;
use App\Services\ReceiptService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get student record
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return view('student.no-profile');
        }

        // Get active allocation
        $allocation = $student->allocation;

        $roommates = collect();
        if ($allocation) {
            $roommates = Student::whereHas('allAllocations', function ($query) use ($allocation) {
                $query->where('room_id', $allocation->room_id)
                    ->where('status', 'active');
            })
                ->where('id', '!=', $student->id)
                ->get();
        }

        $pendingRequest = $student->roomChangeRequests()->where('status', 'pending')->first();

        return view('student.dashboard', compact('student', 'allocation', 'roommates', 'pendingRequest'));
    }

    public function downloadReceipt($allocationId)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $allocation = Allocation::where('id', $allocationId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if (!$allocation->receipt) {
            abort(404, 'Receipt not found');
        }

        $receiptService = new ReceiptService();
        return $receiptService->downloadPDF($allocation->receipt);
    }

    public function generateReceipt($allocationId)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $allocation = Allocation::where('id', $allocationId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($allocation->receipt) {
            return redirect()->back()->with('info', 'Receipt already exists.');
        }

        // Create receipt
        $receipt = \App\Models\Receipt::create([
            'allocation_id' => $allocation->id,
            'student_id' => $student->id,
            'amount' => 50000, // Default amount
            'payment_date' => now(),
            'receipt_number' => \App\Models\Receipt::generateReceiptNumber(),
        ]);

        // Generate PDF
        $receiptService = new ReceiptService();
        $receiptService->generatePDF($receipt);

        return redirect()->back()->with('success', 'Receipt generated successfully.');
    }
}
