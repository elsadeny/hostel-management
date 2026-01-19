<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\Allocation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReceiptService
{
    /**
     * Generate PDF receipt for an allocation
     */
    public function generatePDF(Receipt $receipt): string
    {
        $allocation = $receipt->allocation;
        $student = $receipt->student;

        $data = [
            'receipt' => $receipt,
            'allocation' => $allocation,
            'student' => $student,
            'hostel' => $allocation->hostel,
            'room' => $allocation->room,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('receipts.template', $data);

        // Create filename
        $filename = 'receipts/' . $receipt->receipt_number . '.pdf';

        // Save PDF to storage
        Storage::put('public/' . $filename, $pdf->output());

        // Update receipt with PDF path
        $receipt->update(['pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Download receipt PDF
     */
    public function downloadPDF(Receipt $receipt)
    {
        if (!$receipt->pdf_path || !Storage::exists('public/' . $receipt->pdf_path)) {
            // Generate if doesn't exist
            $this->generatePDF($receipt);
        }

        return Storage::download('public/' . $receipt->pdf_path, $receipt->receipt_number . '.pdf');
    }

    /**
     * Get PDF stream for viewing
     */
    public function streamPDF(Receipt $receipt)
    {
        $allocation = $receipt->allocation;
        $student = $receipt->student;

        $data = [
            'receipt' => $receipt,
            'allocation' => $allocation,
            'student' => $student,
            'hostel' => $allocation->hostel,
            'room' => $allocation->room,
        ];

        return Pdf::loadView('receipts.template', $data)->stream();
    }
}
