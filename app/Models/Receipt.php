<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $fillable = [
        'allocation_id',
        'student_id',
        'amount',
        'payment_date',
        'receipt_number',
        'pdf_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    // Relationships
    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Methods
    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP';
        $year = date('Y');
        $lastReceipt = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastReceipt ? (int) substr($lastReceipt->receipt_number, -6) + 1 : 1;

        return sprintf('%s%s%06d', $prefix, $year, $number);
    }
}
