<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Room;
use App\Models\Hostel;
use App\Models\Allocation;
use App\Models\Receipt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class AllocationService
{
    /**
     * Auto-allocate a student to an appropriate room
     */
    public function autoAllocate(Student $student, string $academicYear): ?Allocation
    {
        try {
            DB::beginTransaction();

            // Find the best room for the student
            $room = $this->findBestRoom($student);

            if (!$room) {
                throw new Exception("No available room found for student");
            }

            // Create allocation
            $allocation = $this->allocateToRoom($student, $room, $academicYear, 'auto');

            // Add occupant to room
            $room->addOccupant();

            // Generate receipt
            $this->generateReceipt($allocation);

            DB::commit();

            // Send notification (will implement later)
            // $this->sendAllocationNotification($allocation);

            return $allocation;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Manual allocation by admin
     */
    public function manualAllocate(Student $student, Room $room, string $academicYear): ?Allocation
    {
        try {
            DB::beginTransaction();

            // Validate gender matching
            if ($student->gender !== $room->hostel->gender && $room->hostel->gender !== 'mixed') {
                throw new Exception("Gender mismatch: Student cannot be allocated to this hostel");
            }

            // Check if room is available
            if ($room->isFull()) {
                throw new Exception("Room is full");
            }

            // Create allocation
            $allocation = $this->allocateToRoom($student, $room, $academicYear, 'manual');

            // Add occupant to room
            $room->addOccupant();

            // Generate receipt
            $this->generateReceipt($allocation);

            DB::commit();

            return $allocation;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Batch allocate multiple students
     */
    public function batchAllocate(Collection $students, string $academicYear): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($students as $student) {
            try {
                $allocation = $this->autoAllocate($student, $academicYear);
                $results['success'][] = [
                    'student' => $student,
                    'allocation' => $allocation,
                ];
            } catch (Exception $e) {
                $results['failed'][] = [
                    'student' => $student,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Find the best available room for a student
     * Priority: 1. Gender 2. Study Level 3. Availability
     */
    protected function findBestRoom(Student $student): ?Room
    {
        // Get hostels matching gender
        $hostels = Hostel::byGender($student->gender)
            ->orWhere('gender', 'mixed')
            ->available()
            ->get();

        if ($hostels->isEmpty()) {
            return null;
        }

        // Find available rooms in matching hostels
        foreach ($hostels as $hostel) {
            $room = Room::where('hostel_id', $hostel->id)
                ->available()
                ->whereColumn('current_occupancy', '<', 'capacity')
                ->orderBy('current_occupancy', 'asc') // Fill rooms evenly
                ->first();

            if ($room) {
                return $room;
            }
        }

        return null;
    }

    /**
     * Create allocation record
     */
    protected function allocateToRoom(Student $student, Room $room, string $academicYear, string $type): Allocation
    {
        // Check if student already has active allocation
        if ($student->hasActiveAllocation()) {
            throw new Exception("Student already has an active allocation");
        }

        return Allocation::create([
            'student_id' => $student->id,
            'room_id' => $room->id,
            'hostel_id' => $room->hostel_id,
            'allocation_date' => now(),
            'status' => 'active',
            'allocation_type' => $type,
            'academic_year' => $academicYear,
        ]);
    }

    /**
     * Generate receipt for allocation
     */
    protected function generateReceipt(Allocation $allocation): Receipt
    {
        $amount = 5000.00; // Default amount (can be made configurable)

        return Receipt::create([
            'allocation_id' => $allocation->id,
            'student_id' => $allocation->student_id,
            'amount' => $amount,
            'payment_date' => now(),
            'receipt_number' => Receipt::generateReceiptNumber(),
        ]);
    }

    /**
     * Deallocate a student (for room changes or leaving)
     */
    public function deallocate(Allocation $allocation): bool
    {
        try {
            DB::beginTransaction();

            // Remove occupant from room
            $allocation->room->removeOccupant();

            // Update allocation status
            $allocation->update(['status' => 'cancelled']);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
